*** Settings ***
Resource    ../resources/publication_resource.robot
Library     DateTime

*** Test Cases ***
TC_007 Export Large Publication Report
    Set Test Variable    ${USERNAME}    chakso@kku.ac.th
    Set Test Variable    ${PASSWORD}    123456789
    Login To System
    Click Export Report
    Click Download PDF
    Close Browser
    Verify File Exists    publication_report_20250225(1).pdf
    Close Browser
    ${pdf_text}=    Read Pdf    ${DOWNLOAD_PATH}publication_report_20250225(1).pdf
    Should Contain    ${pdf_text}    Chakchai So-In, Ph.D.
    Should Contain    ${pdf_text}    chakso@kku.ac.th
    Should Contain    ${pdf_text}    Wireless Sensor Networks and Ad Hoc Networks
    Should Contain    ${pdf_text}    เครื่องสีข้าวอัตโนมัติที่ควบคุมด้วยระบบทางไกลแบบไร้สาย
    Should Contain    ${pdf_text}    แอปพลิเคชันจำกัด (วัดป่าธรรมอุทยาน). (Reference No. 373035).



TC_008 Check Export Speed
    Login To System
    Click Export Report
    
    ${start_pdf}=    Get Current Date    result_format=epoch
    Click Download PDF
    ${end_pdf}=      Get Current Date    result_format=epoch
    ${elapsed_pdf}=  Evaluate    ${end_pdf} - ${start_pdf}
    Log    PDF export elapsed time: ${elapsed_pdf}
    Should Be True   ${elapsed_pdf} < 5

    ${start_docx}=   Get Current Date    result_format=epoch
    Log    Start DOCX export: ${start_docx}
    Click Download DOCX
    ${end_docx}=     Get Current Date    result_format=epoch
    Log    End DOCX export: ${end_docx}
    ${elapsed_docx}=  Set Variable    ${end_docx} - ${start_docx}
    ${elapsed_docx}=  Evaluate    ${elapsed_docx}
    Log    DOCX export elapsed time: ${elapsed_docx}
    Should Be True   ${elapsed_docx} < 5

    Close Browser

TC_009 Verify Missing Data
    Set Test Variable    ${USERNAME}    chakso@kku.ac.th
    Set Test Variable    ${PASSWORD}    123456789
    Login To System
    Click Export Report
    Click Download PDF
    ${pdf_text}=    Read Pdf    ${DOWNLOAD_PATH}publication_report_20250225(1).pdf
    Should Contain    ${pdf_text}    Chakchai So-In, Ph.D.
    Should Contain    ${pdf_text}    chakso@kku.ac.th
    Should Contain    ${pdf_text}    Wireless Sensor Networks and Ad Hoc Networks
    Should Contain    ${pdf_text}    เครื่องสีข้าวอัตโนมัติที่ควบคุมด้วยระบบทางไกลแบบไร้สาย
    Should Contain    ${pdf_text}    แอปพลิเคชันจำกัด (วัดป่าธรรมอุทยาน). (Reference No. 373035).
    Should Contain    ${pdf_text}    No education data available.







