# Contributing to OpenSem

Thank you for your interest in contributing to OpenSem! We welcome contributions of all kinds.

## Development Workflow

1. **Fork the repository**
   ```bash
   # Click the Fork button on GitHub
   ```

2. **Clone your fork**
   ```bash
   git clone https://github.com/luckyops/opensem.git
   cd opensem
   ```

3. **Create a branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

4. **Make your changes**
   - Follow the existing code style
   - Add necessary documentation
   - Ensure all file formats are correct

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "type: brief description"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**
   - Create a PR on GitHub
   - Fill out the PR template
   - Wait for review

## Commit Message Convention

We use semantic commit messages:

- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation update
- `style:` - Code formatting (no functional change)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Build/tooling updates

Examples:
```
feat: add Rust configuration template
fix: correct YAML syntax in python.yml
docs: update README with new examples
```

## Adding New Configuration Templates

1. Create a new YAML file in the `configs/` directory
2. Follow the format of existing templates
3. Update the supported list in README.md
4. Document in CHANGELOG.md

## Adding New Memory Templates

1. Create a new `.md` file in the `templates/` directory
2. Use clear headings and structure
3. Add helpful placeholders and comments
4. Reference the new template in relevant configurations

## Code Review

All Pull Requests must be reviewed before merging. Please ensure:

- [ ] Code follows project style guidelines
- [ ] Documentation has been updated
- [ ] CHANGELOG has been updated
- [ ] No merge conflicts

## Code of Conduct

Please respect all contributors. Friendly and professional communication is a core value of our community.

See [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) for details.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
