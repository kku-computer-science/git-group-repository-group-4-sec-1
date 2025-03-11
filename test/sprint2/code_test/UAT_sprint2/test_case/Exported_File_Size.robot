*** Settings ***
Resource    ../resources/publication_resource.robot


*** Test Cases ***
TC_009 Check Exported File Size
    Login To System
    Click Export Report

    # ดาวน์โหลด PDF และตรวจสอบขนาดไฟล์
    Click Download PDF
    Sleep    5s  
    File Should Exist    ${DOWNLOAD_PATH}publication_report_20250225.pdf
    ${pdf_size}=    Get File Size    ${DOWNLOAD_PATH}publication_report_20250225.pdf
    Log    PDF file size: ${pdf_size} bytes
    Run Keyword If    ${pdf_size} < 10000    Fail    PDF file size is less than 10000 bytes
    Run Keyword If    ${pdf_size} > 5000000    Fail    PDF file size is greater than 5MB

    # ดาวน์โหลด DOCX และตรวจสอบขนาดไฟล์
    Click Download DOCX
    Sleep    5s  
    File Should Exist    ${DOWNLOAD_PATH}publicationreport20250225.docx
    ${docx_size}=    Get File Size    ${DOWNLOAD_PATH}publicationreport20250225.docx
    Log    DOCX file size: ${docx_size} bytes
    Run Keyword If    ${docx_size} < 5000    Fail    DOCX file size is less than 5000 bytes
    Run Keyword If    ${docx_size} > 2000000    Fail    DOCX file size is greater than 2MB

    Close Browser
