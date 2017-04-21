#Variables
$gamedb = "game.db"
$sqlscript = "Reports.sql"

# Set todays date
$now = (get-date).ToShortDateString()
$now = $now.replace('/','_')

#Set Path
#$conanPath = $PWD
$conanPath = "C:\Reports"

# Set File paths to use local
$logsPath = $conanPath
Write-Debug "Path to logs set to: $logsPath"

 # read logfiles to memory
write-host "`nGetting all .log files from $logsPath.." -ForegroundColor Green
$files = get-childitem $logsPath -filter *.log -Recurse

New-Item "$logsPath\Reports_$now" -type directory
$ReportsFolder = "$logsPath\Reports_$now"

# function to search logs for pattern
function search-logs
{
        param(
            [Parameter(Mandatory=$true)]
            $files,
            [Parameter(Mandatory=$true)]
            [string]$pattern,
            [Parameter(Mandatory=$false)]
            [string]$pattern2,
            [Parameter(Mandatory=$false)]
            [string]$context='0,0')            
            # create empty array
            $result =@()            
            # define how many days past logs should be parsed
            write-host "Extracting 7 days back in logs." -ForegroundColor Yellow
            $days = "7"
            Write-Debug "Pattern $pattern Pattern2 $pattern2 Days $days Files $files"            
            $filesFiltered = $files | where {$_.LastWriteTime -ge (Get-Date).AddDays(-$days)}                       
            Write-Debug "Filtered $filesFiltered"
            # loop files, looking for certain pattern in each file
            write-host "Filtering logfiles for data (this takes a while).." -ForegroundColor Green
            foreach ($file in $filesFiltered)
            {
                # check if more than 1 match is requested
                if ($pattern2)
                {                    
                    $result += Get-Content $file.FullName | Select-String -Pattern $pattern -Context $context
                    $result += Get-Content $file.FullName | Select-String -Pattern $pattern2 -Context 0,1                    
                }
                else
                {                    
                    $result += Get-Content $file.FullName | Select-String -Pattern $pattern -Context $context
                }                
            }
            Write-Debug "Data found in logfiles: $result"
            return $result
}

#Work Starts

# Extract Chat Logs
write-host "Extract Chat Logs" -ForegroundColor Green
$chat = search-logs -files $files -pattern 'ChatWindow'            
Write-Host "All chat logs written to file: $logsPath\chat_log_$now.log"
$chat | Out-File "$ReportsFolder\chat-log_$now.log"

write-host "Extract Chat logs Done" -ForegroundColor Green

#Extract Logins
write-host "Extracting Logins" -ForegroundColor Green
#Created by A15 Bog to parse logins for finding exploiters
#https://github.com/A15Bog/conanexilessql
function Get-ScriptDirectory
{ 
if($hostinvocation -ne $null)
{
Split-Path $hostinvocation.MyCommand.path
}
else
{
Split-Path $script:MyInvocation.MyCommand.Path
}
}
$ScriptDirectory = Get-ScriptDirectory
$logdata = gci -Path $ScriptDirectory -r -i *.log | select-string "userId"
$inputfile = $logdata
$outputfile = "$ReportsFolder\logins_$now.csv"
$inputfile = $inputfile | Foreach-Object {
    $_ -replace '[/?]', ',' `
       -replace 'Error:', 'Error ' `
       -replace 'Warning:', 'Warning ' `
       -replace '::', ':' `
       -replace ':', ',' `
       -replace '[\[]', '' `
       -replace ']', ','
}
$inputfile2 = $inputfile
$output = @()
foreach($line in $inputfile2)
	{
	$Date = ($line -split ",")[3]
	$Name = ($line -split ",") -match "Name="
	$SteamID = ($line -split ",") -match "dw_user_id="
	$Name = $Name -replace "Name=",""
	$SteamID = $SteamID -replace "dw_user_id=","steamid="
	$LoginCount = ""
	$output += "`"" + $Date + "`",`"" + $Name + "`",`"" + $SteamID + "`"" + ",`"" + $LoginCount + "`""
	}
$output | Set-Content $outputfile
$filedata = import-csv $outputfile -Header Date , Name , SteamID, LoginCount
foreach ($file in $filedata)
{
$SteamID = ""
$SteamID = $file.SteamID
$LoginCount = $filedata | where {$_.SteamID -match $SteamID} | measure
$file.LoginCount = $LoginCount.Count
}
$filedata = $filedata | Sort-Object -Property {$_.LoginCount},{$_.Name},{$_.Date}  -descending
$filedata | export-csv $outputfile -NoTypeInformation
write-host "Login Extract Done" -ForegroundColor Green

# Call DBReports
write-host "Running DB Reports" -ForegroundColor Green

#start-process $conanpath\DBReports.bat
get-content $sqlscript | .\sqlite3.exe $gamedb
#write-host "Pause for 10 seconds" -ForegroundColor Green
#Start-Sleep -s 10

write-host "DB Reports Done" -ForegroundColor Green

# Rename Output files
Rename-Item allplayers.csv allplayers_$now.csv
Rename-Item buildings_per_owner.csv buildings_per_owner_$now.csv
Rename-Item double_foundation_spam.csv double_foundation_spam_$now.csv
Rename-Item outofbounds.csv outofbounds_$now.csv
Rename-Item single_foundation_spam.csv single_foundation_spam_$now.csv

# Move Output files
write-host "Move Output files" -ForegroundColor Green

Move-Item *.csv $ReportsFolder

write-host "File move Done" -ForegroundColor Green
