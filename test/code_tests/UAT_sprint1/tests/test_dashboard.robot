*** Settings ***
Resource    ../resource/resource.robot

*** Test Cases ***
ทดสอบการเปิดหน้า Dashboard
    Open Browser    http://127.0.0.1:8000/    firefox
    Maximize Browser Window
    Sleep    5s
    Capture Page Screenshot    reports/screenshots/debug_dashboard.png
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Capture Page Screenshot    reports/screenshots/dashboard_loaded.png
    Close Browser

ทดสอบการแสดงผลของกราฟ Dashboard
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Wait Until Element Is Enabled    xpath=//canvas[@id='barchart1']    timeout=30s
    Capture Page Screenshot    reports/screenshots/dashboard_graph.png
    Close Browser

ทดสอบข้อมูลในกราฟ Dashboard
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    ${year_text}    Execute JavaScript    return document.getElementById('year-label').innerText
    Should Contain    ${year_text}    2025
    Close Browser

ทดสอบข้อมูลของกราฟในตาราง
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Table Should Contain    xpath=//table[@id='data-table']    SCOPUS
    Table Should Contain    xpath=//table[@id='data-table']    TCI
    Table Should Contain    xpath=//table[@id='data-table']    WOS
    Table Should Contain    xpath=//table[@id='data-table']    GOOGLE SCHOLAR
    Close Browser

ทดสอบโฮเวอร์และ Tooltip ของกราฟ
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Mouse Over    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//div[@class='tooltip']    timeout=10s
    Capture Page Screenshot    reports/screenshots/tooltip_graph.png
    Close Browser
