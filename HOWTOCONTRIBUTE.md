How to contribute to the project
================================

First of all, test the project by following the instructions in [README.md](https://github.com/YannVogel/phpsymfony-project8/blob/master/README.md).

If you find any issue or if you want to contribute by adding new feature, here are the steps you must follow:

## 1. Create a new issue in the [project issues list](https://github.com/YannVogel/phpsymfony-project8/issues/new).
- The issue title MUST respects this syntax: <br />
<span style="color:#2ca2bf">{Fix/Feature}: {short description of the issue}</span>

  > 👍🏻 <span style="background-color:#024d9e">For example:</span>
  > 
  > Feature: implement ROLE_SUPERADMIN

- The issue content MUST respects this syntax: <br/>
<span style="color:#2ca2bf">
**Current behavior:** {description of the current app's behavior regarding the new fix/feature}. <br/>
**Desired behavior:** {description of the desired app's behavior after the fix/feature is finished}.
</span>

  > 👍🏻 <span style="background-color:#024d9e">For example:</span>
  >
  > **Current behavior:** When a user is created, it's only possible to choose ROLE_ADMIN as a particular role. <br />
  > **Desired behavior:** When a user is created, it must be possible to choose between ROLE_ADMIN and ROLE_SUPERADMIN.

- your issue MUST includes the correct [label](https://docs.github.com/en/issues/using-labels-and-milestones-to-track-work/managing-labels).
  
## 2. Create your local branch, based on dev.
- `git checkout -b <your-new-branch> dev`
- Your branch's name MUST respects this syntax: <br />
  <span style="color:#2ca2bf">{fix/feature}/issue-#{your-issue-number}/{short-description-in-[kebab-case](https://www.theserverside.com/definition/Kebab-case)}</span>

  > 👍🏻 <span style="background-color:#024d9e">For example:</span>
  >
  > git checkout -b feature/issue-#24/implement-super-admin-role dev

## 3. Code... <br />
Some magic is happening here! 😜 <br />
![](https://thumbs.gfycat.com/SpicyHighlevelCavy-max-1mb.gif)

### <span style="color:red">NB: your code MUST respects some [PHP Standards Recommendations](https://www.php-fig.org/psr/).</span>

| PSR | Title                                                              |
|-----|--------------------------------------------------------------------|
| 1   | [Basic coding standard](https://www.php-fig.org/psr/psr-1/)        |
| 4   | [Autoloading standard](https://www.php-fig.org/psr/psr-4/)         |
| 12  | [Extended Coding Style Guide](https://www.php-fig.org/psr/psr-12/) |


## 4. Commit your changes

`git add -u`

`git commit -m '<title-of-your-commit>'`

- The title of your commit MUST respects this syntax: <br />
  <span style="color:#2ca2bf">Issue #{your-issue-number}: {short-description-of-the-commit}</span>

> 👍🏻 <span style="background-color:#024d9e">For example:</span>
>
> git commit -m 'Issue #24: implement super admin role'


## 5. Create the remote branch from your local branch

`git push --set-upstream origin <your-new-branch>`

  > 👍🏻 <span style="background-color:#024d9e">For example:</span>
  >
  > git push --set-upstream origin feature/issue-#24/implement-super-admin-role

## 5. [Create a pull request](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/about-pull-requests) of your branch into dev.

- you MUST [assign a reviewer](https://docs.github.com/en/issues/tracking-your-work-with-issues/assigning-issues-and-pull-requests-to-other-github-users) to your PR (by default, [YannVogel](https://github.com/YannVogel)).
