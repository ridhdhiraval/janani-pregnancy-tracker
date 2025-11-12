<?php
// Dummy contraction record
$contraction_record = [
    'start_time' => '4:33:24 pm',
    'end_time' => '4:33:26 pm',
    'length' => '00:02',
    'rest_time' => '00:00',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contraction Timer</title>
<style>
:root {
    --primary-pink: #ff91a4;
    --soft-pink-bg: #ffe6eb;
    --light-mint-bg: #e0f7f7;
    --light-bg: #fcf8f6;
    --white-bg: #ffffff;
    --text-dark: #333333;
    --text-grey: #6a6a6a;
    --button-red: #ff708e;
    --button-dark: #555;
    --border-light: #f0f0f0;
}

body {
    font-family: 'Segoe UI', Roboto, Arial, sans-serif;
    background-color: var(--light-bg);
    margin: 0;
    padding: 50px 0;
    display: flex;
    justify-content: center;
}

.app-container {
    width: 100%;
    max-width: 1100px; /* wider desktop container */
    background-color: var(--white-bg);
    box-shadow: 0 0 30px rgba(0,0,0,0.15);
    min-height: 650px;
    border-radius: 15px;
    overflow: hidden;
}

.header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 50px;
    font-size: 22px;
    font-weight: 500;
    color: var(--text-dark);
}

.close-icon {
    font-size: 30px;
    font-weight: 300;
    cursor: pointer;
    color: var(--text-grey);
    text-decoration: none;
}

.timer-area {
    background-color: var(--light-mint-bg);
    padding: 80px 60px 50px;
    text-align: center;
}

.timer-title {
    font-size: 24px;
    font-weight: 500;
    color: var(--text-dark);
    display: inline-flex;
    align-items: center;
}

#timer-display {
    font-size: 100px;
    font-weight: 400;
    margin: 40px 0 50px;
    letter-spacing: -2px;
    color: var(--text-dark);
}

#start-stop-button {
    background-color: var(--button-red);
    color: white;
    border: none;
    padding: 25px 90px;
    border-radius: 40px;
    font-size: 26px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 5px 25px rgba(255,112,142,0.4);
    transition: background-color 0.2s;
}

#start-stop-button.running {
    background-color: var(--button-dark);
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.summary-header {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 60px 0 30px;
    text-align: center;
}

.stats-row {
    display: flex;
    justify-content: space-between;
    padding: 0 50px;
    align-items: flex-start;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    padding: 0 25px;
}

.stat-label {
    font-size: 18px;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 10px;
    display: inline-flex;
    align-items: center;
}

.stat-value {
    font-size: 40px;
    font-weight: 400;
    color: var(--text-dark);
    line-height: 1;
}

.stat-value.middle {
    font-size: 60px;
    margin-top: -10px;
}

.stat-value.small {
    font-size: 32px;
}

.history-table-container {
    background-color: var(--white-bg);
    padding: 0 50px 40px;
    margin-top: 50px; 
    border-top: 1px solid var(--border-light);
}

.history-header {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-grey);
    padding: 20px 0 15px;
    border-bottom: 1px solid var(--border-light);
}

.history-header div, .history-row div {
    flex: 1;
    text-align: center;
    min-width: 0; 
}

.history-row {
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    color: var(--text-dark);
    padding: 15px 0;
    border-bottom: 1px solid var(--border-light);
}

.i-icon {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 1px solid var(--text-grey);
    border-radius: 50%;
    text-align: center;
    font-size: 12px;
    line-height: 16px;
    font-weight: bold;
    color: var(--text-grey);
    margin-left: 6px;
}

@media (max-width: 1200px) {
    .app-container { width: 90%; }
    .stats-row { flex-direction: column; gap: 20px; }
    #timer-display { font-size: 80px; }
    #start-stop-button { padding: 20px 70px; font-size: 22px; }
}

</style>
</head>
<body>

<div class="app-container">
    <div class="header-bar">
        <a href="javascript:history.back()" class="close-icon">&times;</a>
        <div class="timer-title">
            Contraction timer <i class="i-icon">i</i>
        </div>
        <span></span>
    </div>

    <div class="timer-area">
        <div id="timer-display">00:00</div>
        <button id="start-stop-button">START</button>
    </div>

    <div style="padding: 0 50px;">
        <div class="summary-header">LATEST NUMBER OF CONTRACTIONS</div>
    </div>
    
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-label">CONTRACTIONS REST <i class="i-icon">i</i></div>
            <div class="stat-value small" id="contractions-rest-display">00:00</div>
        </div>
        <div class="stat-item">
            <div class="stat-label" style="opacity: 0;">&nbsp;</div>
            <div class="stat-value middle" id="total-contractions-display">1</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">TIME BETWEEN CONTRACTIONS <i class="i-icon">i</i></div>
            <div class="stat-value small" id="time-between-display">00:00</div>
        </div>
    </div>

    <div class="history-table-container">
        <div class="history-header">
            <div>START TIME</div>
            <div>END TIME</div>
            <div>LENGTH</div>
            <div>REST TIME</div>
        </div>
        
        <div id="history-table-body">
            <div class="history-row">
                <div><?= htmlspecialchars($contraction_record['start_time']) ?></div>
                <div><?= htmlspecialchars($contraction_record['end_time']) ?></div>
                <div><?= htmlspecialchars($contraction_record['length']) ?></div>
                <div><?= htmlspecialchars($contraction_record['rest_time']) ?></div>
            </div>
        </div>
    </div>
</div>

<script>
const timerDisplay = document.getElementById('timer-display');
const startStopButton = document.getElementById('start-stop-button');
const historyTableBody = document.getElementById('history-table-body');
const totalContractionsDisplay = document.getElementById('total-contractions-display');
const contractionsRestDisplay = document.getElementById('contractions-rest-display');
const timeBetweenDisplay = document.getElementById('time-between-display');

let isContractionRunning = false;
let timerInterval;
let startTime;
let contractionStartTime;
let lastContractionEndTime = null;
let totalContractions = 1;

function formatTime(ms) {
    const totalSeconds = Math.floor(ms/1000);
    const minutes = String(Math.floor(totalSeconds/60)).padStart(2,'0');
    const seconds = String(totalSeconds%60).padStart(2,'0');
    return `${minutes}:${seconds}`;
}

function formatTimeForLog(date) {
    if (!date) return 'N/A';
    const hours = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2,'0');
    const seconds = String(date.getSeconds()).padStart(2,'0');
    const ampm = hours>=12?'pm':'am';
    const displayHours = hours%12||12;
    return `${displayHours}:${minutes}:${seconds} ${ampm}`;
}

function updateTimer() {
    if (!isContractionRunning) return;
    const now = Date.now();
    timerDisplay.textContent = formatTime(now-startTime);
    contractionsRestDisplay.textContent = '00:00';
}

function logContraction() {
    const contractionEndTime = Date.now();
    const contractionLengthMs = contractionEndTime - contractionStartTime;
    let restTimeMs = lastContractionEndTime ? contractionStartTime - lastContractionEndTime : 0;
    const contractionLength = formatTime(contractionLengthMs);
    const restTime = formatTime(restTimeMs);
    const timeBetween = formatTime(contractionEndTime-(lastContractionEndTime?lastContractionEndTime:contractionStartTime));
    
    contractionsRestDisplay.textContent = restTime;
    timeBetweenDisplay.textContent = timeBetween;
    totalContractions++;
    totalContractionsDisplay.textContent = totalContractions;

    const newRow = document.createElement('div');
    newRow.className='history-row';
    newRow.innerHTML=`
        <div>${formatTimeForLog(new Date(contractionStartTime))}</div>
        <div>${formatTimeForLog(new Date(contractionEndTime))}</div>
        <div>${contractionLength}</div>
        <div>${restTime}</div>
    `;
    historyTableBody.prepend(newRow);
    lastContractionEndTime = contractionEndTime;
}

function startStopTimer() {
    if (isContractionRunning){
        clearInterval(timerInterval);
        isContractionRunning=false;
        logContraction();
        startStopButton.textContent='START';
        startStopButton.classList.remove('running');
        startStopButton.style.backgroundColor='var(--button-red)';
        timerDisplay.textContent='00:00';
    } else {
        if(timerInterval) clearInterval(timerInterval);
        contractionStartTime = Date.now();
        startTime = contractionStartTime;
        timerInterval=setInterval(updateTimer,100);
        isContractionRunning=true;
        startStopButton.textContent='STOP';
        startStopButton.classList.add('running');
        startStopButton.style.backgroundColor='var(--button-dark)';
    }
}

startStopButton.addEventListener('click', startStopTimer);
totalContractionsDisplay.textContent=historyTableBody.children.length;
</script>

</body>
</html>
