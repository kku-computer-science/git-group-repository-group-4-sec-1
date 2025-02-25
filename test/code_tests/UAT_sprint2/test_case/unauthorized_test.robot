*** Settings ***
Resource    ../resources/publication_resource.robot

*** Test Cases ***

TC_006 Unauthorized Access
    Open Browser    ${BASE_URL}/export-report    chrome
    Page Should Contain    ErrorException
    Close Browser