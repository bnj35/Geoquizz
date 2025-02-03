const accessToken = "MLY|9499571270087330|a37e1480e6861e8ff45f87d1e270ab36";
const bbox = "6.15,48.65,6.25,48.75"; // Zone de Nancy (France)
const apiUrl = `https://graph.mapillary.com/images?fields=id,thumb_1024_url,geometry&bbox=${bbox}&limit=10&access_token=${accessToken}`;

async function fetchMapillaryImage() {
    try {
        const response = await fetch(apiUrl);
        const data = await response.json();

        if (data.data.length > 0) {
            // Sélectionne une image aléatoire parmi les résultats
            const image = data.data[Math.floor(Math.random() * data.data.length)];
            const imageUrl = image.thumb_1024_url;
            const [lng, lat] = image.geometry.coordinates;

            // Met à jour l'affichage
            document.getElementById("mapillary-image").src = imageUrl;
            document.getElementById("coords").innerText = `Latitude: ${lat}, Longitude: ${lng}`;
        } else {
            document.getElementById("coords").innerText = "Aucune image trouvée.";
        }
    } catch (error) {
        console.error("Erreur lors de la récupération de l'image :", error);
        document.getElementById("coords").innerText = "Erreur lors du chargement.";
    }
}

// Charger une image au démarrage
document.addEventListener("DOMContentLoaded", fetchMapillaryImage);
