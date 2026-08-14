# IssuesBoard <br><img src="https://hackatime.hackclub.com/api/v1/badge/U0B8JTZDTKQ/fabianternis/IssuesBoard">
A dashboard to track issues, bugs and feedback as well as ToDos accross Coding Projects ~~(with option to use GitHub-api)~~.

**Demo:** [issuesboard.thosted.de](https://issuesboard.thosted.de)

### made without the help of AI (not even auto-complete)

### This Codebase is **A BIG MESS**

A Project for macondo by HackClub

## AI use
I only used AI for writing the `.sh`-files and _once_ to find a bug, i searched for for about an hour (i think it was just a misspelled variable).
All other AI-Use in Hackatime are "False Positives" taht happened when i was thinking and wile thinking mass-spamming my keyboard which lead to Wakatime thinking: "There were 10lines changed in 2s. this must be AI" ... SO: i have 2h less of "Actual coding-time".

## Start Dev Server
AFTER CLONING (`git clone https://github.com/fabianternis/IssuesBoard.git`):<br>
_**note:** this might only work on macOS and Linux systems and might not support Windows or any otehr Operating systems <br> this requires you to have PHP and Composer installed on your System_

Give permission `chmod +x ./start.sh`
<br>
Start local PHP Server `./start.sh`
<br>
Open `127.0.0.1:54345`in your Browser

_note: `start.sh`is the only file, ai worked on_

##### Why This way?
Because i may add some more "processes" in the background that then all get started with this OEN script. additionally the port of the webserver is always the same ...


## **basic** Useage instructions/Documentations
### Start: Auth
You start by registering a account (no email-verification is implemented, no username or password minumum-lenghts)<br>
<img width="729" height="67" alt="Screenshot 2026-08-13 at 20 33 32" src="https://github.com/user-attachments/assets/1b0ee873-b2ed-4f5a-810b-0fbadf52898e" />

### First Action: Project creation
Click on "Create Project" on the dashboard and enter something on the form<br>
<img width="729" height="auto" alt="Screenshot 2026-08-13 at 20 34 37" src="https://github.com/user-attachments/assets/8c3a0941-fdb2-48ca-9404-b3ab2b4c3a93" /><br>
Afer **Submit** you will be redirected to the "Project Board"

### Adding the first item
Scroll down to the very-bottom of the page and fill-out the form. Things like "image" and "description" are optional and can also be changed later<br>
<img width="500" height="auto" alt="Screenshot 2026-08-13 at 20 41 13" src="https://github.com/user-attachments/assets/9339e405-aafa-4e2d-9ed0-21b2f51e9a5b" />

### Items
You can "collapse" items to just see the name and no other information(as long as the item is collapsed).<br>You can theoretically have _unlimited_ images uploaded/attached to your item (stored on hackclub's CDN).
<img width="441" height="992" alt="Screenshot 2026-08-13 at 20 43 26" src="https://github.com/user-attachments/assets/459cd7b6-9855-479f-b511-65d042070ddd" />
<br>
<p>Those Items can also be dragged between "types"(Issue/Ideas/ToDo/Other) ...</p>
<br>
<img width="1920" height="992" alt="Screenshot 2026-08-13 at 20 44 13" src="https://github.com/user-attachments/assets/0615fc85-35c2-4f80-9ec7-3246f17b547e" />


## Inspiration
I came-up with the idea for this Project because i always write myself on the "Note to self" signal-channel or create a ToDo.md(with no real overview) on my project.
SO: I decided to create the not that usual "ToDoList"/Taks-management perfect for my needs (linking github-issues/pull-requests and co. (I also wanted to implement GitHub-api but haven't figured taht out yet.

## Used Packages
### blakvghost/php-validator
### ramsey/uuid
### illuminate/database
### knplabs/github-api


Action defines what happens and Object defines how teh "structure" (e.g. form) is ...
---
action=new: show creation form
action=create: create the Object (in DB)


<!--
# Actual Content

## "Board" (main Interface)
- columns for each "type"(currently: Issute/Idea/ToDo/Other) that are colore
- items (forms inside columns that are draggable – auto-save after 5s of nothing happenig after change ...)
... other stuff ...
-->

# My Personal conclusion from this Project
After working on this Project I appreciate _(my have to look up a better fitting word)_ the Work of Developers pre-AI-slop.
This was propably my first time ever working on such a "complex"(it is not actually __that__ complex but just rather complex as a Beginner, _I_ am) project fully without the use of Artificial Slop.
I think taht, after this project, i can relate better to a lot of developers which complains and experiences i hardly understood before
