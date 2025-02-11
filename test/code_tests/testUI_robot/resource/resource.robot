*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BROWSER}    firefox
${URL}    http://127.0.0.1:8000/
${GRAPH}    xpath=//canvas[@id='research-chart']
${YEAR_LABEL}    xpath=//div[@id='year-label']
${TABLE_DATA}    xpath=//table[@id='research-data-table']
${TOOLTIP}    xpath=//div[contains(@class, 'chart-tooltip')]

*** Keywords ***
Open Browser To Dashboard
    Open Browser    ${URL}    ${BROWSER}
    Maximize Browser Window
    Wait Until Page Contains Element    ${GRAPH}    timeout=10s

Verify Graph Is Displayed
    Page Should Contain Element    ${GRAPH}

Verify Year Label
    ${year_text}    Get Text    ${YEAR_LABEL}
    Should Contain    ${year_text}    2025

Verify Table Contains Data
    Table Should Contain    ${TABLE_DATA}    SCOPUS
    Table Should Contain    ${TABLE_DATA}    TCI
    Table Should Contain    ${TABLE_DATA}    WOS
    Table Should Contain    ${TABLE_DATA}    GOOGLE SCHOLAR

Hover Over Graph
    Mouse Over    ${GRAPH}

Verify Tooltip Is Visible
    Wait Until Element Is Visible    ${TOOLTIP}    timeout=5s
