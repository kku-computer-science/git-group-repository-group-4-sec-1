*** Settings ***
Resource    ../resources/publication_resource.robot

*** Test Cases ***
TC_003 Export Publication Report as DOCX
    Login To System
    Click Export Report
    Click Download DOCX
    Verify File Exists    publicationreport20250225.docx
    Close Browser

TC_005 Verify Data In DOCX
    File Should Exist    C:/Users/WINDOWS 11/Downloads/publicationreport20250225.docx
    ${doc_text}=    Evaluate    __import__('doc_reader').read_docx("${DOWNLOAD_PATH}publicationreport20250225.docx")
    Log To Console    ${doc_text}
    Should Contain    ${doc_text}    punhor1@kku.ac.th
    Should Contain    ${doc_text}    Punyaphol Horata, Ph.D.
    Should Contain    ${doc_text}    2528 วท.บ. (คณิตศาสตร์) (มหาวิทยาลัยขอนแก่น)
    Should Contain    ${doc_text}    Machine Learning and Intelligent Systems
    Should Contain    ${doc_text}    Enhanced Local Receptive Fields based Extreme Learning Machine using Dominant

