*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BROWSER}    firefox
${URL}    http://127.0.0.1:8000/
${GRAPH}    xpath=//div[contains(@class, 'chartjs-size-monitor')]/canvas[@id='barchart1']
${YEAR_LABEL}    xpath=//div[@id='year-label']
${TABLE_DATA}    xpath=//table[@id='research-data-table']
${TOOLTIP}    xpath=//div[contains(@class, 'chart-tooltip')]

*** Keywords ***
Open Browser To Dashboard
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window
    Wait Until Page Contains    Report the total number of articles    timeout=30s
    Scroll Element Into View    ${GRAPH}
    Wait Until Element Is Visible    ${GRAPH}    timeout=30s

Verify Graph Is Displayed
    Page Should Contain Element    ${GRAPH}
    Capture Page Screenshot    reports/screenshots/graph_displayed.png

Verify Year Label
    ${year_text}    Execute JavaScript    return document.getElementById('year-label').innerText
    Should Contain    ${year_text}    2025
    Capture Page Screenshot    reports/screenshots/year_label.png

Verify Table Contains Data
    Wait Until Element Is Visible    ${TABLE_DATA}    timeout=30s
    Table Should Contain    ${TABLE_DATA}    SCOPUS
    Table Should Contain    ${TABLE_DATA}    TCI
    Table Should Contain    ${TABLE_DATA}    WOS
    Table Should Contain    ${TABLE_DATA}    GOOGLE SCHOLAR
    Capture Page Screenshot    reports/screenshots/table_data.png

Hover Over Graph
    Scroll Element Into View    ${GRAPH}
    Mouse Over    ${GRAPH}

Verify Tooltip Is Visible
    Wait Until Element Is Visible    ${TOOLTIP}    timeout=5s
    Capture Page Screenshot    reports/screenshots/tooltip_visible.png

