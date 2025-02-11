*** Settings ***
Library    SeleniumLibrary
Resource   ../resource/keywords.robot
Resource   ../resource/locators.robot

*** Test Cases ***
Navigate To Researcher Profile
    Open Browser To Home Page
    Hover Researchers Menu
    Click Element    ${COMPUTER_SCIENCE}
    Wait Until Element Is Visible    ${RESEARCHER_PROFILE}    timeout=10s
    Click Element    ${RESEARCHER_PROFILE}
    Sleep    2s
    Page Should Contain    Punyaphol Horata
    SeleniumLibrary.Close Browser
