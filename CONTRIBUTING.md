# Contributing to ToDo & CO
## Introduction

Thanks for taking the time to contribute!

This file is a set of guidelines for contributing to ToDo & Co App improvement.
Following these guidelines helps to communicate that you respect the time of the developers managing and developing ToDo & Co App. In return, they should reciprocate that respect in addressing your issue, assessing changes, and helping you finalize your pull requests.

## Code of Conduct

- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Respecting all points of view and be cool with less
- Gracefully accepting constructive criticism
- Focusing your intention on what is best for the community
- Keeping in mind that is a Teamwork.

## How to contribute
1) **For**k the [repo](https://github.com/olha-r/toDoList).
2) **Install** the project locally
If you haven't already, install the project on your machine via Git, following the installation instructions in the [Readme file](Readme.md).
3) **Create a new branch**, taking care to name it in a coherent and understandable way (in English preferably), e.g. feature-foo or bugfix-bar.
4) **Make** your **code changes**, dividing into multiple commits if necessary. Write commit messages preferably in English.
5) **Test** your changes. Prefer adding new test cases over modifying existing ones.
Run the tests to verify that they always pass after your changes: ``make test``
6) Then **update the coverage test file** for Codacy, with the following command:
``bash <(curl -Ls https://coverage.codacy.com/get.sh) report  -r reports/clover.xml``
7) Push your changes and **create a pull request**.
More details about PR on  [GitHub documentation.](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests)

**If your contribution is approved, it will be merged into the main branch of the project.**

**Thanks in advance for contribution!**
