Duplicating this repository  
---
  <div class="article-body content-body wikistyle markdown-format">
      <div class="intro">

<p>To create a duplicate of a repository without forking, you need to run a special clone command against the original repository and mirror-push to the new one.  This works with any git repository, not just ones hosted on GitHub.</p>

</div>

<p>In the following cases, the repository you're trying to push to--like <code>someuser/new-repository</code>--should already exist on GitHub.

<h3>
<a name="lets-do-this-thing" class="anchor" href="#lets-do-this-thing"><span class="mini-icon mini-icon-link"></span></a>Let's do this thing!</h3>

<p>To make an exact duplicate, you need to perform both a bare-clone and a mirror-push:</p>

```bash
# move into temp folder
cd /tmp
# clone a bare repo of phpspec-skeleton
git clone --bare git@github.com:dariushha/phpspec-skeleton.git
# move into cloned repo
cd phpspec-skeleton.git
# push a mirror to any empty repo 
git push --mirror git push --mirror git@github.com:someuser/new-repository.git
# moveback to temp folder
cd ..
# remove the bare repo of phpspec-skeleton
rm -fr phpspec-skeleton.git
# move to your project directory
cd my-fav-directory
# clone the mirrored repo
git clone git@github.com:someuser/new-repository.git
````

Source: <small>[Duplicating a repository](https://help.github.com/articles/duplicating-a-repository).</small>
