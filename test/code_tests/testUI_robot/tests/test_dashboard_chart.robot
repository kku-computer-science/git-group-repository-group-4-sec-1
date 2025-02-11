*** Settings ***
Library    SeleniumLibrary
Resource   ../resource/keywords.robot
Resource   ../resource/locators.robot

*** Test Cases ***
Check Dashboard Chart Display
    Open Browser To Dashboard
    Wait Until Element Is Visible    ${CHART_CONTAINER}    timeout=20s
    Element Should Be Visible    ${CHART_CONTAINER}
    SeleniumLibrary.Close Browser
