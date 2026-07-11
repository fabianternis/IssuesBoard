# IssuesBoard <br><img src="https://hackatime.hackclub.com/api/v1/badge/U0B8JTZDTKQ/fabianternis/IssuesBoard">
A dashboard to track issues, bugs and feedback as well as ToDos accross Coding Projects (with option to use GitHub-api).

### made without the help of AI (not even auto-complete)

A Project for macondo by HackClub


## Start Dev Server
Give permission `chmod +x ./start.sh`
<br>
Start local PHP Server `./start.sh`
<br>
Open `127.0.0.1:54345`in your Browser

_note: `start.sh`is the only file, ai worked on_

##### Why This way?
Because i may add some more "processes" in the background that then all get started with this OEN script. additionally the port of the webserver is always the same ...


## Used Packages
### blakvghost/php-validator
### ramsey/uuid
### illuminate/database
### knplabs/github-api


Action defines what happens and Object defines how teh "structure" (e.g. form) is ...
---
action=new: show creation form
action=create: create the Object (in DB)
