# Releasing `reaper/ui` to Packagist (git subtree workflow)

This repo (`Ui`) is a full Laravel sandbox app used to **develop and test** the
`reaper/ui` package locally (via the path repository + symlink in the root
`composer.json`). The package itself lives at `packages/reaper/ui/`.

Packagist cannot use this repo directly — it would ship the entire sandbox
app to every consumer. Instead, `packages/reaper/ui/` is split out into its
own dedicated repository (e.g. `HAJDARODAVID/reaper-ui`) using `git subtree`,
and *that* repo is the one registered on Packagist.

```
Ui (this repo, "develop")              reaper-ui (dedicated repo, "master")
  packages/reaper/ui/  ──git subtree──►  composer.json
  <rest of sandbox app>                  src/
                                          config/
                                          resources/
                                          README.md
```

All commands below are run **from the root of this repo** (`Ui`), unless
stated otherwise.

---

## 0. One-time prerequisites

- [ ] Create a **completely empty** GitHub repo for the package, e.g.
      `https://github.com/HAJDARODAVID/reaper-ui` — do **not** initialize it
      with a README, `.gitignore`, or license. An empty repo avoids merge
      conflicts on the very first push.
- [ ] Add a `LICENSE` file inside `packages/reaper/ui/` (root `composer.json`
      of the package already declares `"license": "MIT"` — Packagist expects
      an actual `LICENSE` file to match).
- [ ] Confirm `packages/reaper/ui/composer.json` has a correct `"name"`
      (`reaper/ui`) — Packagist requires the submitted repo's `composer.json`
      name to match what you register.

---

## 1. One-time setup: register the remote

```bash
git remote add reaper-ui-origin https://github.com/HAJDARODAVID/reaper-ui.git
```

Verify it was added:

```bash
git remote -v
```

---

## 2. One-time setup: first split + push

Extract the history of `packages/reaper/ui/` into a local branch, then push
that branch as `master` on the new remote:

```bash
git subtree split --prefix=packages/reaper/ui -b package-split
git push reaper-ui-origin package-split:master
```

Clean up the temporary local branch (optional — you can also leave it and
reuse/reset it next time):

```bash
git branch -D package-split
```

**Verify:** clone `reaper-ui` somewhere fresh and confirm `composer.json`
sits at the repo root and `"name": "reaper/ui"`.

---

## 3. Day-to-day development

Keep working exactly as you do now:

- Edit files under `packages/reaper/ui/` in this repo.
- Commit to this repo's normal branch(es) as usual.
- Test live against the sandbox app — the root `composer.json` path
  repository is symlinked, so changes are picked up immediately without any
  publish step.

Nothing about your daily workflow changes. The subtree sync only happens
when you're ready to ship an update to the published package.

> If you want a strict split between "package work" and "sandbox app work",
> do the package edits on a `develop` branch in this repo and only run the
> sync/release steps below from `develop` (or after merging `develop` into
> whatever branch you sync from). The sync commands don't care which branch
> you're on — they only look at the current `packages/reaper/ui/` content.

---

## 4. Sync changes to the package repo (no new version yet)

Whenever you want to push the latest package code to the dedicated repo
without cutting a release:

```bash
git subtree push --prefix=packages/reaper/ui reaper-ui-origin master
```

This re-splits automatically (git subtree caches previous split points, so
this is fast and incremental) and fast-forwards `reaper-ui-origin/master`.

---

## 5. Cut a release (semver tag)

Packagist versions come from **tags** on the package repo, so after syncing:

```bash
# 1. sync latest package code
git subtree push --prefix=packages/reaper/ui reaper-ui-origin master

# 2. fetch the remote-tracking ref so we have the exact synced commit locally
git fetch reaper-ui-origin master

# 3. create an annotated tag on that commit (follow semver, e.g. v1.0.0)
git tag -a v1.0.0 reaper-ui-origin/master -m "v1.0.0"

# 4. push just the tag
git push reaper-ui-origin v1.0.0
```

Repeat with `v1.1.0`, `v1.0.1`, etc. for future releases. Use standard
[semver](https://semver.org/) rules:

- `vMAJOR.MINOR.PATCH`
- bump **patch** for fixes, **minor** for backwards-compatible features,
  **major** for breaking changes.

---

## 6. Submitting to Packagist (one-time)

1. Log in to [packagist.org](https://packagist.org) (GitHub OAuth is
   easiest).
2. Click **Submit**, paste the repo URL:
   `https://github.com/HAJDARODAVID/reaper-ui`
3. Packagist validates `composer.json` and lists the package once accepted.
4. Set up auto-update so new tags/pushes are picked up without a manual
   click:
   - Easiest: install the **Packagist** GitHub App on the `reaper-ui` repo
     (Packagist package page → *Settings* → shows the exact link).
   - Manual alternative: on the `reaper-ui` GitHub repo → *Settings* →
     *Webhooks* → *Add webhook*:
     - Payload URL: `https://packagist.org/api/github?username=<your-packagist-username>`
     - Content type: `application/json`
     - Secret: your Packagist API token (from your Packagist profile page)
     - Events: just the `push` event

After this, every `git push` / tag push to `reaper-ui` (step 4/5 above)
updates Packagist automatically within seconds.

---

## 7. Cheat sheet

| Goal | Command |
|---|---|
| Add the package remote (once) | `git remote add reaper-ui-origin https://github.com/HAJDARODAVID/reaper-ui.git` |
| First-ever push (once) | `git subtree split --prefix=packages/reaper/ui -b package-split && git push reaper-ui-origin package-split:master` |
| Sync latest code, no release | `git subtree push --prefix=packages/reaper/ui reaper-ui-origin master` |
| Cut a release `vX.Y.Z` | sync (above) → `git fetch reaper-ui-origin master` → `git tag -a vX.Y.Z reaper-ui-origin/master -m "vX.Y.Z"` → `git push reaper-ui-origin vX.Y.Z` |
| Pull a change made directly on the package repo back into this repo (avoid if possible) | `git subtree pull --prefix=packages/reaper/ui reaper-ui-origin master --squash` |

---

## Troubleshooting

- **`git subtree push` fails with "rejected" / diverged history**: this
  happens if someone committed directly to `reaper-ui-origin/master` outside
  of this workflow. Run `git subtree pull --prefix=packages/reaper/ui
  reaper-ui-origin master --squash` first to merge those changes back into
  this repo, then push again. Avoid editing the package repo directly —
  always edit here in `packages/reaper/ui/` and sync outward.
- **First push rejected**: make sure the GitHub repo was created truly empty
  (no README/license/`.gitignore` auto-added). If it wasn't, either delete
  and recreate it empty, or force the very first push:
  `git push reaper-ui-origin package-split:master --force` (safe only
  because the remote repo is new and disposable at this point).
