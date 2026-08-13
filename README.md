# IssuesBoard <br><img src="https://hackatime.hackclub.com/api/v1/badge/U0B8JTZDTKQ/fabianternis/IssuesBoard">
A dashboard to track issues, bugs and feedback as well as ToDos accross Coding Projects (with option to use GitHub-api).

**Demo:** [issuesboard.thosted.de](https://issuesboard.thosted.de)

### made without the help of AI (not even auto-complete)

### This Codebase is **A BIG MESS**

A Project for macondo by HackClub


## Start Dev Server
AFTER CLONING (`git clone https://github.com/fabianternis/IssuesBoard.git`):
_**note:** this might only work on macOS and Linux systems and might not support Windows or any otehr Operating systems <br> this requires you to have PHP and Composer installed on your System_

<br><br>

Give permission `chmod +x ./start.sh`
<br>
Start local PHP Server `./start.sh`
<br>
Open `127.0.0.1:54345`in your Browser

_note: `start.sh`is the only file, ai worked on_

##### Why This way?
Because i may add some more "processes" in the background that then all get started with this OEN script. additionally the port of the webserver is always the same ...


## **basic** Useage instructions/Documentations

Images: ToDo


## Used Packages
### blakvghost/php-validator
### ramsey/uuid
### illuminate/database
### knplabs/github-api


Action defines what happens and Object defines how teh "structure" (e.g. form) is ...
---
action=new: show creation form
action=create: create the Object (in DB)



# Actual Content

## "Board" (main nterface)
- columns for each "type"(currently: Issute/Idea/ToDo/Other) that are colore
- items (forms inside columns that are draggable – auto-save after 5s of nothing happenig after change ...)
... other stuff ...


# My Personal conclusion from this Project
After working on this Project I appreciate _(my have to look up a better fitting word)_ the Work of Developers pre-AI-slop.
This was propably my first time ever working on such a "complex"(it is not actually __that__ complex but just rather complex as a Beginner, _I_ am) project fully without the use of Artificial Slop.
I think taht, after this project, i can relate better to a lot of developers which complains and experiences i hardly understood before