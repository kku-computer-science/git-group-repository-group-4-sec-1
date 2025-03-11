*** Settings ***
Resource    ../resources/publication_resource.robot

*** Test Cases ***
TC_001 User Can Login And Access Publication Report
    Login To System
    Click Export Report
    Page Should Contain    Download as PDF
    Page Should Contain    Download as Word
    Close Browser
