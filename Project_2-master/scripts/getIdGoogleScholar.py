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
        }
        return profile_data
    else:
        return None

# Example Usage
if __name__ == "__main__":
    f = open("name.txt","r")
    names = f.read()
    names = names.split("\n")
    
    d = open("idname.txt","a")
    n = open("notFondname.txt","a")
    for name in names:
        profile = get_scholar_profile(name)
        if not profile:
            print(name+" not found")
            n.write(name+"\n")
            continue
            
        print(profile["name"],",",profile["scholar_id"])
        d.write(profile["name"]+","+profile["scholar_id"]+"\n")
