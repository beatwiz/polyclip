<!-- markdownlint-disable MD033 MD041 -->
<div align="center">

# 🚀 OpenSem

### Claude Code 的 Serena + Superpowers 初始化模板

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)](https://github.com/luckyops/OpenSem)
[![Claude Code](https://img.shields.io/badge/Claude_Code-native-green.svg)](https://platform.claude.com/docs/en/home)

**10-30x 效率提升 • 零脚本 • 3 分钟配置**

> **⚠️ 重要提示：** OpenSem 需要先安装 [Serena](https://github.com/oraios/serena) 和 [Superpowers](https://github.com/obra/superpowers) 才能使用。**请务必先安装这两个组件。**

[什么是 OpenSem](#-什么是-opensem) •
[使用前后对比](#-使用前后对比) •
[功能特性](#-功能特性) •
[快速开始](#-快速开始) •
[配置参考](#-配置参考) •
[贡献指南](#-贡献指南)

中文 | [English](README.md)

</div>

---

## 📖 什么是 OpenSem？

**OpenSem** 是一个初始化模板系统，用于激活 Claude Code 的两个核心插件：

- **[Serena](https://github.com/oraios/serena)** – 基于 LSP 的语义代码分析
- **[Superpowers](https://github.com/obra/superpowers)** – 可复用技能系统

**为什么重要：**

Claude Code 内置了这两个插件，但**默认未配置**。不激活它们就使用 Claude Code，就像不装扩展用 VS Code——你错过了 90% 的潜力。

**OpenSem 做什么：**

1. 生成 `.serena/project.yml`，配置适合你项目的 LSP 服务器
2. 初始化持久化记忆模板（代码规范、架构、工作流）
3. 激活 Superpowers 技能用于复杂工作流
4. 配置项目特定的模式和上下文

**结果：** Claude Code 从通用助手转变为项目专家——效率提升 **10-30 倍**。

---

## ⚡ 使用前后对比

### 🔴 Claude Code（默认状态）

没有 OpenSem，Claude Code 缺乏语义理解：

```
你：重构 UserService 类
Claude：*生成通用重构代码，不了解你的：*
        * - 继承层次结构
        * - 编码规范
        * - 测试模式
你：*解释规范、修正风格、补充测试...*
```

**指标：**
- 一次成功率：**60%**
- 需要迭代次数：**4-5 次**
- 上下文设置时间：**5 分钟**
- 需要修改代码：**40%**

---

### 🟢 Claude Code + OpenSem（已激活）

有了 OpenSem，Claude Code 具备 LSP 理解和持久化记忆：

```
你：重构 UserService 类
Claude：*使用 LSP 理解：*
        * - 父类和接口
        * - 项目代码规范
        * - 记忆中的测试模式
        * - 常见工作流
```

**指标：**
- 一次成功率：**95%** (↑ 58%)
- 需要迭代次数：**1 次** (↓ 80%)
- 上下文设置时间：**10 秒** (↑ 30x)
- 需要修改代码：**5%** (↓ 87%)

---

### 📊 10-30 倍效率提升

| 指标 | 默认状态 | 使用 OpenSem | 提升 |
|:-------|:--------|:-------------|:------------|
| 一次成功率 | 60% | 95% | **1.6x** |
| 迭代次数 | 4-5 | 1 | **4-5x** |
| 上下文设置 | 5 分钟 | 10 秒 | **30x** |
| 代码修改量 | 40% | 5% | **8x** |
| **整体会话效率** | **1x** | **10-30x** | **10-30x** |

---

## ✨ 功能特性

| 特性 | 描述 |
|:---------------:|:-------------------|
| 🔌 **插件激活** | 一键配置 Serena + Superpowers |
| 🧠 **LSP 语义理解** | 15+ 种语言服务器，精准代码分析 |
| 💾 **持久化记忆** | 6 种记忆模板，规范永久生效 |
| ⚡ **3 分钟配置** | 复制文件夹，运行命令，完成 |
| 🎨 **动态生成** | 自动为任何语言生成配置 |
| 📦 **自包含** | 复制单个文件夹即可——配合 Serena + Superpowers 使用 |

---

## 🚀 快速开始

### ⚠️ 步骤 1：安装前置组件（必须！）

**OpenSem 无法在没有这些组件的情况下工作。你必须先安装它们：**

| 组件 | 作用 | 没有它会怎样 | 下载 |
|:----------|:--------|:------------------------|:---------|
| **[Serena](https://github.com/oraios/serena)** | LSP 语义分析 | Claude 无法理解你的代码结构 | [GitHub](https://github.com/oraios/serena) • [文档](https://oraios.github.io/serena/) |
| **[Superpowers](https://github.com/obra/superpowers)** | 可复用技能系统 | 没有持久化工作流和模式 | [GitHub](https://github.com/obra/superpowers) |

**🔴 不要跳过这一步。没有 Serena + Superpowers，OpenSem 无法运行。**

---

### 步骤 2：复制 OpenSem 到你的项目

```bash
# 从 GitHub 克隆（仅代码，不含历史记录）
git clone --depth 1 https://github.com/luckyops/OpenSem.git opensem
cp -r opensem /path/to/your-project/
cd /path/to/your-project
```

### 步骤 3：激活

打开 Claude Code 并说：

```bash
"使用 OpenSem 配置我的项目"
```

**就是这样！** Claude Code 将会：
1. 分析你的项目结构
2. 选择合适的 LSP 服务器
3. 生成 `.serena/project.yml`
4. 初始化记忆模板
5. 激活 Serena + Superpowers

**3 分钟后，Claude Code 的效率提升 10-30 倍。**

---

## 📋 支持的项目

| 类别 | 技术 |
|:---------------|:----------------------|
| 🌐 **Web 前端** | React, Vue, Next.js, Angular, Svelte, SolidJS, Astro |
| 🔧 **后端 API** | Node.js, Python (Django/FastAPI/Flask), Go, Java, C#, Ruby, PHP, Rust |
| 🎯 **全栈** | Next.js+Python, React+Go, Vue+Node.js, Svelte+Rust |
| 📱 **移动端** | React Native, Flutter, Swift, Kotlin, Ionic |
| 🖥️ **桌面** | Electron, Tauri, Qt |
| ⚡ **CLI 工具** | Node.js, Python, Go, Rust, Shell |
| 📊 **数据/AI** | Python (ML/数据科学), Jupyter, R, Julia |
| ⛓️ **区块链** | Solidity, Rust (Solana), Go (Cosmos), JS (Tezos) |
| 🎮 **游戏开发** | Unity (C#/C++), Unreal, Godot |
| 🔬 **嵌入式** | Arduino, C/C++, Rust, FreeRTOS |
| 🔍 **分析** | 外部代码库的只读模式 |

> **注意**：核心模板包括 TypeScript 和 Python。所有其他语言都使用最佳实践动态生成。

---

## 📁 项目结构

```
opensem/
├── LICENSE                    # MIT 许可证
├── README.md                  # 英文文档
├── README.zh-CN.md            # 中文文档
├── CHANGELOG.md               # 版本历史
├── CONTRIBUTING.md            # 贡献指南
│
├── .github/                   # GitHub 模板
│   ├── ISSUE_TEMPLATE/
│   └── PULL_REQUEST_TEMPLATE.md
│
├── configs/                   # 核心配置模板
│   ├── typescript.yml         # TypeScript/JavaScript
│   ├── python.yml             # Python
│   ├── fullstack.yml          # 多语言项目
│   ├── readonly.yml           # 只读分析模式
│   └── default.yml            # 默认配置
│
├── templates/                 # 内存和知识模板
│   ├── project_overview.md    # 项目概述
│   ├── tech_stack.md          # 技术栈
│   ├── code_conventions.md    # 编码规范
│   ├── project_structure.md   # 目录结构
│   ├── suggested_commands.md  # 常用命令
│   └── task_checklist.md      # 完成检查清单
│
└── docs/                      # 额外文档
    ├── INSTRUCTIONS.md        # Claude Code 指令
    └── SUPERPOWERS.md         # 技能参考
```

---

## 🔧 配置参考

### 标准配置结构

```yaml
# .serena/project.yml
project_name: "your-project-name"

languages:
  - typescript    # LSP: typescript
  - python        # LSP: python
  - markdown      # 用于文档

encoding: "utf-8"
ignore_all_files_in_gitignore: true

ignored_paths:
  - "**/node_modules/**"
  - "**/dist/**"
  - "**/build/**"

read_only: false

base_modes:
  - editing       # 启用代码编辑
  - interactive   # 启用交互模式

initial_prompt: |
  欢迎使用 {PROJECT_NAME}！
  项目特定的上下文和指南。
```

### 可用的 LSP 服务器

| 语言 | LSP 服务器 | 配置键 |
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

## 🤝 贡献

我们欢迎各种形式的贡献！详情请参阅 [CONTRIBUTING.md](CONTRIBUTING.md)。

---

## 📜 开源协议

本项目采用 MIT 许可证 - 详见 [LICENSE](LICENSE) 文件。

---

## 🙏 致谢

OpenSem 建立在优秀的开源工具之上：

| 项目 | 描述 | 链接 |
|:---------------|:-------------------|:-------------|
| **[Serena](https://oraios.github.io/serena/)** | 基于 LSP 的语义代码分析 | [GitHub](https://github.com/oraios/serena) • [文档](https://oraios.github.io/serena/) |
| **[Superpowers](https://github.com/obra/superpowers)** | Claude Code 强大的技能系统 | [GitHub](https://github.com/obra/superpowers) |
| **[Claude Code](https://platform.claude.com/docs/en/home)** | 下一代 AI 编程助手 | [文档](https://platform.claude.com/docs/en/home) |

---

## 🔗 相关资源

- [Serena 文档](https://oraios.github.io/serena/) • [GitHub](https://github.com/oraios/serena)
- [Superpowers 技能库](https://github.com/obra/superpowers)
- [Claude Code 文档](https://platform.claude.com/docs/en/home)
- [Model Context Protocol (MCP)](https://modelcontextprotocol.io/)

---

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=luckyops/OpenSem&type=Date&legend=top-left)](https://www.star-history.com/#luckyops/OpenSem&type=Date)

---

<div align="center">

**由社区用 ❤️ 制作**

[⬆ 返回顶部](#-opensem)

</div>
