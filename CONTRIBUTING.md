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
composer update-tools
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
composer coverage
```

### 4. Run the PHP quality tools

```bash
composer quality
```

This checks PHP coding style, static analysis, and code-quality rules.
To apply PHP-CS-Fixer changes automatically, run the following command and then run `composer quality` again:

```bash
composer cs-fix
```

### 5. Run Markdown linting

Markdown checks require Node.js. Install the local tools:

```bash
npm install --ignore-scripts
```

Check all project Markdown files while excluding dependency directories:

```bash
composer markdown
```

Apply Markdown fixes where they are safe to apply, then run the check again:

```bash
npm run lint:markdown -- --fix
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

## Composer commands

Prepare the PHP and Markdown tools before running quality checks:

```bash
composer install
composer update-tools
npm install --ignore-scripts
```

`composer update-tools` updates only the four isolated PHP quality environments.
Use `composer update` separately to update the library dependencies. Composer
lockfiles remain local and ignored.

| Command | Purpose |
| --- | --- |
| `composer test` | Run PHPUnit without coverage |
| `composer coverage` | Generate coverage reports |
| `composer coverage-check` | Check the coverage threshold |
| `composer cs-check` | Check formatting without changing files |
| `composer cs-fix` | Apply formatting corrections |
| `composer phpstan` | Run static analysis |
| `composer phpmd` | Analyze production code and tests |
| `composer phpcs` | Check coding standards |
| `composer markdown` | Check Markdown |
| `composer quality` | Check PHP and Markdown without changing source files |
| `composer update-tools` | Update PHP quality tools |
| `composer audit-dependencies` | Audit PHP dependencies |
| `composer check` | Run all checks |

No minimum coverage percentage is configured; the coverage check requires a
valid, non-empty report.

## Continuous integration

Pull requests run the supported PHP combinations and a separate Markdown check
when relevant files change. Superseded pull request runs are cancelled.
The `CI` check summarizes the relevant jobs, including documentation-only changes.

CI uses `composer coverage-ci` with `phpunit.ci.xml` to generate only
`build/coverage/clover.xml` for Codecov. No HTML/text coverage reports or coverage
artifacts are produced. `composer coverage` keeps the local reports unchanged.
After relevant changes reach `main`, a lightweight workflow updates Codecov
without repeating the PHP quality checks or compatibility matrix.

Weekly runs resolve current dependencies and collect the complete matrix results.
Composer and npm caches contain downloads; lockfiles remain local and ignored.
Matrix jobs without coverage disable the coverage driver. They ignore only the
optional development requirement `ext-pcov` during dependency installation.
