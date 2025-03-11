*** Settings ***
Resource    ../resources/publication_resource.robot

*** Test Cases ***
TC_002 Export Publication Report as PDF
    Login To System
    Click Export Report
    Click Download PDF
    Verify File Exists    publication_report_20250224.pdf
    Close Browser

TC_004 Verify Data In PDF
    ${pdf_text}=    Read Pdf    ${DOWNLOAD_PATH}publication_report_20250224.pdf
    Should Contain    ${pdf_text}    punhor1@kku.ac.th
    Should Contain    ${pdf_text}    Punyaphol Horata, Ph.D.
    Should Contain    ${pdf_text}    2528 วท.บ. (คณิตศาสตร์) (มหาวิทยาลัยขอนแก่น)
    Should Contain    ${pdf_text}    Machine Learning and Intelligent Systems
    Should Contain    ${pdf_text}    Enhanced Local Receptive Fields based Extreme Learning Machine using Dominant
    Should Contain    ${pdf_text}    ภาษาโปรแกรม (Programming languages). ขอนแก่น: คลังนานาธรรม
