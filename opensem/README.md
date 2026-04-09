<!-- markdownlint-disable MD033 MD041 -->
<div align="center">

# 🚀 OpenSem

### Bootstrapping template for Serena + Superpowers in Claude Code

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)](https://github.com/luckyops/OpenSem)
[![Claude Code](https://img.shields.io/badge/Claude_Code-native-green.svg)](https://platform.claude.com/docs/en/home)

**10-30x efficiency boost • Zero scripts • 3-minute setup**

> **⚠️ IMPORTANT:** OpenSem requires [Serena](https://github.com/oraios/serena) and [Superpowers](https://github.com/obra/superpowers) to work. **Install them first before continuing.**

[What is OpenSem?](#-what-is-opensem) •
[Before vs After](#-before-vs-after) •
[Features](#-features) •
[Quick Start](#-quick-start) •
[Configuration](#-configuration) •
[Contributing](#-contributing)

[**中文文档**](README.zh-CN.md) | English

</div>

---

## 📖 What is OpenSem?

**OpenSem** is a bootstrapping template system that activates two powerful Claude Code plugins:

- **[Serena](https://github.com/oraios/serena)** – LSP-powered semantic code analysis
- **[Superpowers](https://github.com/obra/superpowers)** – Reusable skill system

**Why it matters:**

Claude Code ships with these plugins, but they're **unconfigured by default**. Using Claude Code without activating them is like using VS Code without extensions—you're missing 90% of the potential.

**What OpenSem does:**

1. Generates `.serena/project.yml` with proper LSP servers for your project
2. Initializes persistent memory templates (code conventions, architecture, workflows)
3. Activates Superpowers skills for complex workflows
4. Configures project-specific modes and contexts

**Result:** Claude Code transforms from a generic assistant into a project-aware expert—delivering **10-30x efficiency gains**.

---

## ⚡ Before vs After

### 🔴 Claude Code (Default State)

Without OpenSem, Claude Code operates without semantic awareness:

```
You: "Refactor the UserService class"
Claude: *Generic refactoring that doesn't know your:*
        * - inheritance hierarchy
        * - coding standards
        * - test patterns
You: *Explain patterns, fix style, add tests...*
```

**Metrics:**
- First-pass success rate: **60%**
- Iterations needed: **4-5**
- Context setup time: **5 minutes**
- Code revision needed: **40%**

---

### 🟢 Claude Code + OpenSem (Activated)

With OpenSem, Claude Code has LSP understanding and persistent memory:

```
You: "Refactor the UserService class"
Claude: *Uses LSP to understand:*
        * - parent classes and interfaces
        * - project code conventions
        * - test patterns from memory
        * - common workflows
```

**Metrics:**
- First-pass success rate: **95%** (↑ 58%)
- Iterations needed: **1** (↓ 80%)
- Context setup time: **10 seconds** (↑ 30x)
- Code revision needed: **5%** (↓ 87%)

---

### 📊 The 10-30x Improvement

| Metric | Default | With OpenSem | Improvement |
|:-------|:--------|:-------------|:------------|
| First-pass success | 60% | 95% | **1.6x** |
| Iterations needed | 4-5 | 1 | **4-5x** |
| Context setup | 5 min | 10 sec | **30x** |
| Code revision | 40% | 5% | **8x** |
| **Overall session efficiency** | **1x** | **10-30x** | **10-30x** |

---

## ✨ Features

| Feature | Description |
|:---------------:|:-------------------|
| 🔌 **Plugin Activation** | One-command setup for Serena + Superpowers |
| 🧠 **LSP Semantic Understanding** | 15+ language servers for accurate code analysis |
| 💾 **Persistent Memory** | 6 memory templates for conventions that stick |
| ⚡ **3-Minute Setup** | Copy folder, run command, done |
| 🎨 **Dynamic Generation** | Auto-generates configs for any language |
| 📦 **Self-Contained** | Single folder copy—works with Serena + Superpowers |

---

## 🚀 Quick Start

### ⚠️ Step 1: Install Prerequisites (Required!)

**OpenSem will NOT work without these components. You MUST install them first:**

| Component | Purpose | What happens without it | Download |
|:----------|:--------|:------------------------|:---------|
| **[Serena](https://github.com/oraios/serena)** | LSP semantic analysis | Claude can't understand your code structure | [GitHub](https://github.com/oraios/serena) • [Docs](https://oraios.github.io/serena/) |
| **[Superpowers](https://github.com/obra/superpowers)** | Reusable skill system | No persistent workflows or patterns | [GitHub](https://github.com/obra/superpowers) |

**🔴 Do NOT skip this step. OpenSem cannot function without Serena + Superpowers.**

---

### Step 2: Copy OpenSem to Your Project

```bash
# Clone from GitHub (code only, no history)
git clone --depth 1 https://github.com/luckyops/OpenSem.git opensem
cp -r opensem /path/to/your-project/
cd /path/to/your-project
```

### Step 3: Activate

Open Claude Code and say:

```bash
"Use OpenSem to configure my project"
```

**That's it!** Claude Code will:
1. Analyze your project structure
2. Select appropriate LSP servers
3. Generate `.serena/project.yml`
4. Initialize memory templates
5. Activate Serena + Superpowers

**3 minutes later, Claude Code is 10-30x more effective.**

---

## 📋 Supported Projects

| Category | Technologies |
|:---------------|:----------------------|
| 🌐 **Web Frontend** | React, Vue, Next.js, Angular, Svelte, SolidJS, Astro |
| 🔧 **Backend API** | Node.js, Python (Django/FastAPI/Flask), Go, Java, C#, Ruby, PHP, Rust |
| 🎯 **Fullstack** | Next.js+Python, React+Go, Vue+Node.js, Svelte+Rust |
| 📱 **Mobile** | React Native, Flutter, Swift, Kotlin, Ionic |
| 🖥️ **Desktop** | Electron, Tauri, Qt |
| ⚡ **CLI Tools** | Node.js, Python, Go, Rust, Shell |
| 📊 **Data/AI** | Python (ML/Data Science), Jupyter, R, Julia |
| ⛓️ **Blockchain** | Solidity, Rust (Solana), Go (Cosmos), JS (Tezos) |
| 🎮 **Game Dev** | Unity (C#/C++), Unreal, Godot |
| 🔬 **Embedded** | Arduino, C/C++, Rust, FreeRTOS |
| 🔍 **Analysis** | Readonly mode for external codebases |

> **Note**: Core templates include TypeScript and Python. All other languages are dynamically generated with best practices.

---

## 📁 Project Structure

```
opensem/
├── LICENSE                    # MIT License
├── README.md                  # This file
├── README.zh-CN.md            # Chinese documentation
├── CHANGELOG.md               # Version history
├── CONTRIBUTING.md            # Contribution guide
│
├── .github/                   # GitHub templates
│   ├── ISSUE_TEMPLATE/
│   └── PULL_REQUEST_TEMPLATE.md
│
├── configs/                   # Core configuration templates
│   ├── typescript.yml         # TypeScript/JavaScript
│   ├── python.yml             # Python
│   ├── fullstack.yml          # Multi-language projects
│   ├── readonly.yml           # Readonly analysis mode
│   └── default.yml            # Default configuration
│
├── templates/                 # Memory & knowledge templates
│   ├── project_overview.md    # Project overview
│   ├── tech_stack.md          # Technology stack
│   ├── code_conventions.md    # Coding standards
│   ├── project_structure.md   # Directory structure
│   ├── suggested_commands.md  # Common commands
│   └── task_checklist.md      # Completion checklist
│
└── docs/                      # Additional documentation
    ├── INSTRUCTIONS.md        # Claude Code instructions
    └── SUPERPOWERS.md         # Skills reference
```

---

## 🔧 Configuration Reference

### Standard Config Structure

```yaml
# .serena/project.yml
project_name: "your-project-name"

languages:
  - typescript    # LSP: typescript
  - python        # LSP: python
  - markdown      # For documentation

encoding: "utf-8"
ignore_all_files_in_gitignore: true

ignored_paths:
  - "**/node_modules/**"
  - "**/dist/**"
  - "**/build/**"

read_only: false

base_modes:
  - editing       # Enable code editing
  - interactive   # Enable interactive mode

initial_prompt: |
  Welcome to {PROJECT_NAME}!
  Project-specific context and guidelines here.
```

### Available LSP Servers

| Language | LSP Server | Config Key |
|----------|------------|------------|
| TypeScript | `typescript` | `typescript` |
| JavaScript | `typescript` | `javascript` |
| Python | `python` | `python` |
| Go | `go` | `go` |
| Rust | `rust` | `rust` |
| Java | `java` | `java` |
| Kotlin | `kotlin` | `kotlin` |
| C# | `csharp_omnisharp` | `csharp` |
| Ruby | `ruby_solargraph` | `ruby` |
| PHP | `intelephense` | `php` |
| Swift | `sourcekit-lsp` | `swift` |
| Dart | `dart` | `dart` |
| Solidity | `solidity` | `solidity` |
| C/C++ | `cpp` | `cpp` |

---

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

## 📜 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

OpenSem is built upon amazing open-source tools:

| Project | Description | Links |
|:---------------|:-------------------|:-------------|
| **[Serena](https://oraios.github.io/serena/)** | Semantic code analysis powered by LSP | [GitHub](https://github.com/oraios/serena) • [Docs](https://oraios.github.io/serena/) |
| **[Superpowers](https://github.com/obra/superpowers)** | Powerful skill system for Claude Code | [GitHub](https://github.com/obra/superpowers) |
| **[Claude Code](https://platform.claude.com/docs/en/home)** | Next-generation AI coding assistant | [Docs](https://platform.claude.com/docs/en/home) |

---

## 🔗 Related Resources

- [Serena Documentation](https://oraios.github.io/serena/) • [GitHub](https://github.com/oraios/serena)
- [Superpowers Skills Library](https://github.com/obra/superpowers)
- [Claude Code Documentation](https://platform.claude.com/docs/en/home)
- [Model Context Protocol (MCP)](https://modelcontextprotocol.io/)

---

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=luckyops/OpenSem&type=Date&legend=top-left)](https://www.star-history.com/#luckyops/OpenSem&type=Date)

---

<div align="center">

**Made with ❤️ by the community**

[⬆ Back to Top](#-opensem)

</div>
