/**
 * Main JavaScript for ASIO MVP
 * Phase 2: Frontend Interactions with Accessibility
 */

// Show space details when card is clicked
function showSpaceDetails(spaceId) {
  alert("Space ID: " + spaceId + "\n\nDetailed view coming soon!");
  // TODO: Navigate to space detail page or show modal
}

// Search functionality
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");

  if (searchInput) {
    searchInput.addEventListener("input", function (e) {
      const searchTerm = e.target.value.toLowerCase();
      const spaceCards = document.querySelectorAll(".space-card");

      spaceCards.forEach((card) => {
        const spaceName = card.querySelector("h3").textContent.toLowerCase();
        const spaceDesc = card.querySelector(".space-description")
          ? card.querySelector(".space-description").textContent.toLowerCase()
          : "";

        if (spaceName.includes(searchTerm) || spaceDesc.includes(searchTerm)) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    });
  }

  // Add keyboard support for space cards
  const spaceCards = document.querySelectorAll(".space-card");
  spaceCards.forEach((card) => {
    card.addEventListener("keydown", function (e) {
      // Enter or Space key
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        card.click();
      }
    });
  });
});
