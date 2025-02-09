from scholarly import scholarly,ProxyGenerator

def setup_proxy():
    pg = ProxyGenerator()
    success = pg.FreeProxies()  # Use free proxies
    # Alternative: pg.SingleProxy("http://your-proxy-address:port") for a custom proxy
    if success:
        scholarly.use_proxy(pg)
    else:
        print("Failed to set up proxy")

def get_scholar_profile(name):
    search_query = scholarly.search_author(name)
    author = next(search_query, None)  # Get the first match

    if author:
        author_filled = scholarly.fill(author)
        profile_data = {
            "name": author_filled.get("name"),
            "affiliation": author_filled.get("affiliation"),
            "interests": author_filled.get("interests"),
            "scholar_id": author_filled.get("scholar_id"),
            "cited_by": author_filled.get("citedby"),
            "publications": [
                scholarly.fill(pub)
                #scholarly.search_pubs(pub["bib"]["title"])
                for pub in author_filled["publications"][:3]  # Fetch top 5 publications
            ],
        }
        return profile_data
    else:
        return {"error": "No profile found"}

# Example Usage
if __name__ == "__main__":
    name = "Punyaphol Horata"
    profile = get_scholar_profile(name)
    print(profile)

#The objective of this research is to design and develop a display framework of a criminal mapping system that is effective and applicable for all areas. The researchers have emphasized the design areas of data management for any desired area. The users can access each data display and define the size and center point of the primary map that is needed to deal with the criminal mapping. There are 2 types of criminal mapping that can be displayed, the point mapping and choropleth mapping, and the system also can show the details of each crime area including important surrounding areas in both regular and satellite maps. For a crime mapping test, the investigators took crime data from the Police Forensic Science Center 4 as a case study. In the design and development of the display framework for the crime mapping system the researchers found that the developed crime mapping system can work effectively and can be used in other areas without further development. In their evaluation of the system, the users overall satisfaction was at the highest level ( = 4.64, S.D. = 0.54).
