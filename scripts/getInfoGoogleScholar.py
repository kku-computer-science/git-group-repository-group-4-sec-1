from scholarly import scholarly

def get_scholar_profile(scholar_id):
    try:
        # Search for the author by ID
        author = scholarly.search_author_id(scholar_id)
        author = scholarly.fill(author)  # Get full profile details
        
        # Print relevant profile details
        profile_data = {
            "Name": author.get("name"),
            "Affiliation": author.get("affiliation"),
            "Citations": author.get("citedby"),
            "H-index": author.get("hindex"),
            "i10-index": author.get("i10index"),
            "Interests": author.get("interests"),
            "Publications": [pub["bib"]["title"] for pub in author.get("publications", [])]
        }

        return profile_data

    except Exception as e:
        return {"error": str(e)}

# Example usage
scholar_id = "eMdpRLEAAAAJ"  # Replace with a valid Google Scholar ID
profile = get_scholar_profile(scholar_id)
print(profile)
