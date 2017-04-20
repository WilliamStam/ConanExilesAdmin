# Todo List

## Web Interface

#### Dashboard
 - System Logs
     - [ ] Scans run
         - [ ] Scan results
     - [ ] Bans added / removed / changed
         - Manual IP
         - Player bans
 - [ ] Players found to be cheating in logs
     - [ ] Show action taken 
     - [ ] Allow action to be taken
     
#### Players
 - Filtering
     - [x] Active users
     - [x] Inactive users
     - [x] Search
 - [ ] Clickable for more details on the player 
 - [ ] Steam id clickable to take you to the profile page
 - [ ] Pagination
 

#### Blacklist
 - Filtering
     - [ ] Search
 - Manual IP
     - [x] form for add / edit
     - [ ] remove a record
 - Ban List
     - [ ] clickable record to show the player's details page
     
#####  Scan
 
##### Settings
###### Parameters
 - [ ] Login restrictions (warning and auto ban parameters)
 - [ ] Active users time ago
 - [ ] Game DB location
 - [ ] Login log file location
 - [ ] Green wall co-ordinates
 

###### Users
 - [x] add users
 - [x] edit users
 - [x] remove users
     
## Parser
#### Players
 - [x] Players Details  - game.db
 - [ ] Players IP - login log file
 

#### Action parsers
 - [ ] login logout exploits
 - [ ] green wall
     
## Scripts   
 - firewall rules
     - [ ] Manual ip ban list
         - resembles the Manual IP list under blacklist
         - adding a new IP refreshes this script
     - [ ] Player Ban List
     	- ip gets removed if not used in last X hours (to stop IP recycling issues)
     	- whenever the system detects a banned user is active it will run this to update the IP block list