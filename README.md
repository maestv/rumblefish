Rumblefish Notes
================

Various Notes
-------------

To merge commits, just run the command:

> git mergetool \[SHA1\] \[SHA2\] -- \[filename\]

Where \[SHA1\] and \[SHA2\] are the original SHA and the SHA you want to
merge (respectively) and \[filename\] is the file you want to merge.
To see which SHAs you want to merge, just run the command:

> git log \[filename\]
