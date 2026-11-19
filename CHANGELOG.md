# Changelog

All notable changes to this project will be documented in this file. See [commit-and-tag-version](https://github.com/absolute-version/commit-and-tag-version) for commit guidelines.

## 1.4.0 (2026-11-19)

### ✨ New Features — 1.4.0

* **wkb:** add shared enum correspondences ([4bd5605](https://github.com/longitude-one/spatial-core/commit/4bd5605958a027078b8a67fb2fbf73258bb97af3))

### ♻️ Refactoring — 1.4.0

* reorganize quality tools and update configuration files ([1d74d52](https://github.com/longitude-one/spatial-core/commit/1d74d523011a4a15e347ce5f17e5d599b1d2b5b2))
* **tests:** Refactor assertions in tests to use 'self::' instead of 'static::' for consistency ([fbe5b14](https://github.com/longitude-one/spatial-core/commit/fbe5b146da32ca954b3a40e9bbed4591326fb251))
* update CI configuration, improve coverage reporting, and enhance quality tool commands ([dc1edb8](https://github.com/longitude-one/spatial-core/commit/dc1edb85e7ee788f4780a6601d21fe5f344f2031))

### 📚 Documentation — 1.4.0

* update CONTRIBUTING.md and add README files for quality tools and tests ([c7b8cbb](https://github.com/longitude-one/spatial-core/commit/c7b8cbbe6429e04eb96ec17c6bc6ddaef32c8868))

### 👷 CI/CD — 1.4.0

* add 'dom' extension to CI configuration for Composer ([81e531a](https://github.com/longitude-one/spatial-core/commit/81e531a14c8a752def68408135fd31423f975419))
* update CI configuration, add phpunit.ci.xml for coverage, and enhance documentation ([a0eea60](https://github.com/longitude-one/spatial-core/commit/a0eea608cc69cabebcd1c057418204d6706eba83))

### 🔧 Maintenance — 1.4.0

* add blank line after PHP opening tag in coverage-check.php for consistency ([61a67cd](https://github.com/longitude-one/spatial-core/commit/61a67cdae049943e60db9419f80d4f6b2286bc46))
* add blank lines for consistency and readability in multiple files ([0fcc7b4](https://github.com/longitude-one/spatial-core/commit/0fcc7b472566cbbdd575a2386ca1e24494d99e5f))
* add ext-dom dev dependency to composer.json ([f517d12](https://github.com/longitude-one/spatial-core/commit/f517d12a7f43e10e45e4fb78ef2837219f118bc4))
* migrate PHPMD configurations to v3 ([0effe67](https://github.com/longitude-one/spatial-core/commit/0effe67b1726816b005338095197fe77321baa7c))
* refactor readme-version-updater to improve version extraction and update logic ([c288ef8](https://github.com/longitude-one/spatial-core/commit/c288ef85ebbc16b76ef9f3df4cc904b1e0225fae))
* Remove composer.lock from tools ([937f65c](https://github.com/longitude-one/spatial-core/commit/937f65cc7c9c173b4e43622235771fbd3ceefd3f))
* remove markdownlint configuration file ([97424da](https://github.com/longitude-one/spatial-core/commit/97424da7eab8752ae1da7491037ae773dfc8aedd))
* specify types for constants in enums and classes ([ab08697](https://github.com/longitude-one/spatial-core/commit/ab08697204fe009533a6c7896dffd668a2b9582a))
* **tests:** Replace `self` with `static` in test assertions ([4c1621e](https://github.com/longitude-one/spatial-core/commit/4c1621e93e0c687f0df5244576520cc64116217e))
* update .gitattributes to improve export-ignore rules and maintain consistency ([17c9fc9](https://github.com/longitude-one/spatial-core/commit/17c9fc94bd4997842f4c11277c2a1cb9fd3b08b8))
* update CONTRIBUTING.md and README for testing and coverage instructions ([a28dcfc](https://github.com/longitude-one/spatial-core/commit/a28dcfcc6ab3653c3c26d64a79c20bf034cd48b7))
* update file headers to reflect project name and copyright information ([4ea7753](https://github.com/longitude-one/spatial-core/commit/4ea775372623619122d353a091f189246a130fac))
* update markdownlint configuration and improve changelog formatting ([8772f29](https://github.com/longitude-one/spatial-core/commit/8772f29a4fc70dde70106a2672a77b0e45085ebe))
* Update PHP-CS-Fixer configuration** ([001e1c1](https://github.com/longitude-one/spatial-core/commit/001e1c127449051fce02a6a42b94d7188e96678c))
* update PHPMD ruleset to exclude StaticAccess for consistency ([9a50aa1](https://github.com/longitude-one/spatial-core/commit/9a50aa1ea332220deee33473e2f0107f6c58d820))
* update README installation command to allow version range and add versioning context to configuration ([0f75543](https://github.com/longitude-one/spatial-core/commit/0f75543054b0473d55cbb3f2a7e1d1998a36a40d))

### 📊​ Quality tools — 1.4.0

* **PHP-CS-FIXER:** update PHP-CS-Fixer configuration ([0050324](https://github.com/longitude-one/spatial-core/commit/0050324bfe9ce8153ee054027105fd74182501c3))
* **PHP-Stan:** Update phpstan dependencies and clean up configuration files ([2836fb8](https://github.com/longitude-one/spatial-core/commit/2836fb8504a045cf1b039c0edb19ecb3cdb069d8))

## [1.3.0](https://github.com/longitude-one/spatial-core/compare/1.2.0...1.3.0) (2026-10-03)

### ✨ New Features (1.3.0)

* Add additional ISO geometry types and update classification ([49f1a8e](https://github.com/longitude-one/spatial-core/commit/49f1a8eea67135726cdff346a28ec3e3b32271a1))

### 📚 Documentation (1.3.0)

* Add repository guidelines and update markdownlint configuration ([49bc5de](https://github.com/longitude-one/spatial-core/commit/49bc5de7f78cb8352e078bf079b1dd3b4fdff5eb))

## [1.2.0](https://github.com/longitude-one/spatial-core/compare/1.1.0...1.2.0) (2026-09-28)

### ✨ New Features (1.2.0)

* Add volume geometry type ([5336fa2](https://github.com/longitude-one/spatial-core/commit/5336fa200459579d469b38b77a421e5b1199ea8f))
* Implement instantiability classification ([b545e20](https://github.com/longitude-one/spatial-core/commit/b545e20458ad282929e21c8649fd9699d17715fa))

### 🔧 Maintenance (1.2.0)

* PSR-4 improved ([088be4f](https://github.com/longitude-one/spatial-core/commit/088be4f5e506ffc6d846eeb4d5b3b01587d955d9))

## [1.1.0](https://github.com/longitude-one/spatial-core/compare/1.0.0...1.1.0) (2026-09-04)

### ✨ New Features (1.1.0)

* Implement DiagnosticValueFormatter ([#4](https://github.com/longitude-one/spatial-core/issues/4)) ([db6f205](https://github.com/longitude-one/spatial-core/commit/db6f205768ace5ae3c9bbc1a3736f4926681799b))

### 🔧 Maintenance (1.1.0)

* Update copyright information and remove outdated project references ([9977b41](https://github.com/longitude-one/spatial-core/commit/9977b41d95dad522f6e50b8edab0ff3a73fba0dd))

### 📗​ PHPUnit tests (1.1.0)

* Update tests to handle malformed UTF-8 bytes and adjust expected output ([fcb98fb](https://github.com/longitude-one/spatial-core/commit/fcb98fb87b64e62c2b527b53b663a5edab8eb496))

## 1.0.0 (2026-08-30)

### ✨ New Features (1.0.0)

* Add Coordinate and topological dimensions enumerations. ([06d60bb](https://github.com/longitude-one/spatial-core/commit/06d60bbb991d02ff59622c718e3eac555dc99522))
* Add enums for UnitType, CoordinateReferenceSystemKind, and ByteOrder with corresponding tests. ([620b1eb](https://github.com/longitude-one/spatial-core/commit/620b1eb662d35de0d374f807314401e467e1cb96))
* Add spatial model enumeration. ([27940c3](https://github.com/longitude-one/spatial-core/commit/27940c3491a3c63891cd788d5238a1d27ba6ab02))
* Enhance GeometryTypeEnum with additional cases. ([c1533f3](https://github.com/longitude-one/spatial-core/commit/c1533f30f19718d6b24fd11de82b95fb9f4d7c46))
* Implement GeometryTypeEnum and corresponding unit tests. ([38499d9](https://github.com/longitude-one/spatial-core/commit/38499d9f05397fcd53f465c129140be1bf5cc49f))

### 📚 Documentation (1.0.0)

* Documentation updated. ([d33e855](https://github.com/longitude-one/spatial-core/commit/d33e855939e017ac6992920e4914d0bb7ec8c6bf))

### 👷 CI/CD (1.0.0)

* Setup CI workflow ([#2](https://github.com/longitude-one/spatial-core/issues/2)) ([cbb6a6a](https://github.com/longitude-one/spatial-core/commit/cbb6a6a53ffef4d2e18ab43cd52c219de09b5f77))

### 🔧 Maintenance (1.0.0)

* Classes moved ([459ad96](https://github.com/longitude-one/spatial-core/commit/459ad963f88cdd47d7b0b6f211e1bf6500783336))
* Initial commit ([1367fe5](https://github.com/longitude-one/spatial-core/commit/1367fe5f4ed657be582a5830704dca15ba252b06))

### 📊​ Quality tools (1.0.0)

* Add missing PhpDoc ([28a6a8d](https://github.com/longitude-one/spatial-core/commit/28a6a8d280125b724c7890e8bd41f04376d09db0))
* Rules improved. ([15282de](https://github.com/longitude-one/spatial-core/commit/15282de67dd52a759689098cf35924855680956b))

### 📗​ PHPUnit tests (1.0.0)

* Security test added. ([55f9b07](https://github.com/longitude-one/spatial-core/commit/55f9b07e86334877c5297e8b5dcb2b5e3b368b8a))
