# Contributing

Thank you for contributing to `longitude-one/spatial-core`.

## Before committing

Run the following checks from the project root before pushing a commit. A contribution is ready to push only when every command completes successfully and the working tree contains the intended changes.

### 1. Install project dependencies

Install the project dependencies after cloning the repository:

```bash
composer install
```

Install or update the tools used by the quality checks when they are missing or need to be refreshed:

```bash
composer upgrade-tools
```

### 2. Validate Composer metadata

```bash
composer validate --strict
```

### 3. Run the test suite

```bash
composer test
```

When changing executable code, generate the coverage reports as an additional local check:

```bash
composer test-coverage
```

### 4. Run the PHP quality tools

```bash
composer quality
```

This checks PHP coding style, static analysis, and code-quality rules.
To apply PHP-CS-Fixer changes automatically, run the following command and then run `composer quality` again:

```bash
composer quality-fix
```

### 5. Run Markdown linting

Markdownlint-cli2 requires Node.js. Install it first, then install the linter:

```bash
npm install --global markdownlint-cli2
```

Check all project Markdown files while excluding dependency directories:

```bash
composer markdownlint
```

Apply Markdown fixes where they are safe to apply, then run the check again:

```bash
markdownlint-cli2 --config quality/markdownlint/.markdownlint-cli2.json --fix
```

### 6. Review the final change set

```bash
git diff --check
git status --short
```

Review the remaining changes, add only the files that belong to the commit, and push once the checks above are green.

## Commit message convention

Commit messages follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```text
<type>(optional scope): short description
```

Use one of the types declared in `.versionrc.json`:

| Type       | Use it for                                       |
| ---------- | ------------------------------------------------ |
| `feat`     | A new feature                                    |
| `fix`      | A bug fix                                        |
| `perf`     | A performance improvement                        |
| `refactor` | A code change without a feature or bug fix       |
| `docs`     | Documentation only                               |
| `eco`      | An environmental-impact improvement              |
| `ci`       | Continuous-integration or delivery configuration |
| `chore`    | Maintenance work                                 |
| `quality`  | Quality-tool configuration or maintenance        |
| `test`     | Test additions or changes                        |

Examples:

```text
feat(axis): add cardinal directions
fix(range): report the correct axis error
chore: update development tools
```
