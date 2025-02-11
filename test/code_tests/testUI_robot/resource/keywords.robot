*** Settings ***
Library    SeleniumLibrary

*** Variables ***
${BROWSER}    Chrome
${HOME_URL}   http://127.0.0.1:8000
${DASHBOARD_URL}   http://127.0.0.1:8000

*** Keywords ***
Open Browser To Home Page
    Open Browser    ${HOME_URL}    ${BROWSER}
    Maximize Browser Window

Open Browser To Dashboard
    Open Browser    ${DASHBOARD_URL}    ${BROWSER}
    Maximize Browser Window

Close Browser
    Close Browser

Hover Researchers Menu
    Mouse Over    xpath=//a[contains(text(), 'RESEARCHERS')]
