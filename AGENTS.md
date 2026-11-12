# Repository Guidelines

## Project Structure & Module Organization

`longitude-one/spatial-core` provides shared PHP primitives for LongitudeOne spatial libraries.
PSR-4 maps `LongitudeOne\Core\` to `lib/`, which contains `Enum/`, `Exception/`, and `Diagnostic/`.
Tests live in `tests/Unit/` and `tests/Contract/` under `LongitudeOne\Core\Tests\`.
Quality configurations and isolated tool dependencies live in `quality/`; CI lives in `.github/workflows/`.
Consult `README.md` for public behavior and `CONTRIBUTING.md` for the full contribution checklist.

## Build, Test, and Development Commands

Use PHP 8.3+ with `mbstring` and Composer. This library has no application server or asset build.

- `composer install`: install library development dependencies.
- `composer update-tools`: update only the four isolated quality-tool environments; inspect resulting changes.
- `composer validate --strict`: validate package metadata.
- `composer test`: run PHPUnit.
- `composer coverage`: generate coverage reports under `build/coverage/`; requires a coverage driver.
- `composer quality`: run PHP-CS-Fixer checks, PHPStan level 9, PHPMD, and PHP_CodeSniffer.
- `composer cs-fix`: apply PHP-CS-Fixer formatting; rerun quality checks afterward.
- `composer markdown`: lint Markdown; run `npm install --ignore-scripts` first.
- `git diff --check`: detect whitespace errors before committing.

## Coding Style & Naming Conventions

Use four-space indentation, LF endings, strict types, explicit types, and the existing copyright header.
Follow the configured Symfony/PHP-CS-Fixer and PSR-2-based rules.
Use PascalCase class names, camelCase methods and variables, and uppercase enum cases.
Match filenames to symbols, such as `GeometryTypeEnum.php`; preserve established enum backing values.

## Testing Guidelines

Use PHPUnit 12.5 with `*Test.php` classes and descriptive `test...` methods.
Mirror source organization in unit tests; place public-contract checks in `tests/Contract/`.
Run focused tests with `composer test -- --filter GeometryTypeEnumTest`.
Cover boundary cases, exceptions, and regressions. When extending an enum, verify every method for the added cases.
Generate coverage for executable changes; no numeric coverage minimum is configured in PHPUnit.

## Commit & Pull Request Guidelines

Follow Conventional Commits, as in history: `feat: ...`, `fix: ...`, or `chore: ...`.
Use optional scopes, for example `fix(range): report the correct axis error`.
Link the issue, explain behavioral changes, and report validation results in the PR.
All required checks must pass before pushing. Obtain independent review and Alexandre’s merge decision.
Do not mark work Done before merge. Escalate unresolved public API, compatibility, architecture, or normative decisions to Alexandre.

CI coverage uses `composer coverage-ci` and `phpunit.ci.xml` (Clover XML only).
Local `composer coverage` reports are unchanged. See `CONTRIBUTING.md` for triggers,
cache policy and the aggregate `CI` check.
