# AGENTS.md

Conventions for everyone (humans and AI agents) working in this repository.

## Communication

- Everything in this repository is English: issues, pull requests, code comments, commit messages.
- Issues and pull requests go to GitHub; work happens on feature branches named `feature/<issue>-<topic>`.
- The package author (sneakyx) reviews and merges pull requests. Never merge your own pull request.

## Code style

- PHP 8.2+, strict types everywhere, readonly properties where possible.
- Follow the style of the existing code; keep the public API small and typed.
- Namespaces: `Sneakyx\MotisLaravelClient\` in `src/`, `Sneakyx\MotisLaravelClient\Tests\` in `tests/`.
- The underlying API calls live in motis-php-client; this layer adds Laravel integration only.

## Testing

- PHPUnit with orchestra/testbench; all tests offline with mocked `MotisClient` instances.
- Generated models are not final, so `createMock` works for them.
- Run with `composer test`. CI runs the matrix over the supported Laravel versions.

## Workflow

1. Create an issue describing the change.
2. Branch off main, implement, add tests.
3. Open a pull request, wait for CI and review.
