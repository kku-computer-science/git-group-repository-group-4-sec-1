*** Settings ***
Resource    ../resource/resource.robot

*** Test Cases ***
ทดสอบการเปิดหน้า Dashboard
    Open Browser To Dashboard
    Close Browser

ทดสอบการแสดงผลของกราฟ Dashboard
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Capture Page Screenshot    reports/screenshots/dashboard_graph.png
    Close Browser

ทดสอบข้อมูลในกราฟ Dashboard
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    ${year_text}    Get Text    xpath=//div[@id='year-label']
    Should Contain    ${year_text}    2025
    Close Browser

ทดสอบข้อมูลของกราฟในตาราง
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Table Should Contain    ${TABLE_DATA}    SCOPUS
    Table Should Contain    ${TABLE_DATA}    TCI
    Table Should Contain    ${TABLE_DATA}    WOS
    Table Should Contain    ${TABLE_DATA}    GOOGLE SCHOLAR
    Close Browser

ทดสอบโฮเวอร์และ Tooltip ของกราฟ
    Open Browser To Dashboard
    Sleep    5s
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    xpath=//canvas[@id='barchart1']    timeout=30s
    Mouse Over    xpath=//canvas[@id='barchart1']
    Wait Until Element Is Visible    ${TOOLTIP}
    Capture Page Screenshot    reports/screenshots/tooltip_graph.png
    Close Browser
