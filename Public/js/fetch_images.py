import requests
import json
import time

# URL de base de l'API avec filtre de licence
base_url = "https://api.openverse.org/v1/images/?q=computer+hardware&license=cc0,by,by-sa"

# Initialiser une liste pour stocker tous les résultats
all_results = []
result_count = 0
page_count = 0

# Récupérer toutes les pages
page = 1
while True:
    print(f"Récupération de la page {page}...")
    # Ajouter le numéro de page à l'URL
    url = f"{base_url}&page={page}"
    try:
        response = requests.get(url, timeout=10)
        if response.status_code != 200:
            print(f"Erreur lors de la récupération de la page {page} : Code HTTP {response.status_code}")
            break

        data = response.json()
        # Mettre à jour le nombre total et le nombre de pages
        if page == 1:
            result_count = data["result_count"]
            page_count = data["page_count"]
            print(f"Nombre total d'images : {result_count}, Nombre de pages : {page_count}")

        # Ajouter les résultats de cette page
        if data["results"]:
            all_results.extend(data["results"])
            print(f"Images récupérées pour la page {page} : {len(data['results'])}")
        else:
            print(f"Aucune image trouvée pour la page {page} (page vide ou ignorée)")

        # Passer à la page suivante
        page += 1
        # Si on a atteint la dernière page, arrêter
        if page > page_count:
            break

        # Pause de 5 secondes pour éviter le rate limiting
        time.sleep(5)

    except Exception as e:
        print(f"Erreur lors de la récupération de la page {page} : {e}")
        break

# Créer le JSON final
final_json = {
    "result_count": result_count,
    "page_count": page_count,
    "page_size": 20,
    "page": 1,
    "results": all_results
}

# Sauvegarder dans image.json
try:
    with open("image.json", "w") as file:
        json.dump(final_json, file, indent=4)
    print("Fichier image.json créé avec succès !")
    print(f"Nombre total d'images dans le fichier : {len(all_results)}")
except Exception as e:
    print(f"Erreur lors de la sauvegarde de image.json : {e}")