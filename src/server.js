// Wait for the DOM to finish loading
document.addEventListener("DOMContentLoaded", function (event) {
  // Get the table element
  const table = document.querySelector(".result-table");

  // Store the current sort order for each column
  const sortOrder = new Array(table.querySelectorAll("th").length).fill("asc");

  // Attach a click event listener to the table headers
  table.querySelectorAll("th").forEach((header) => {
    header.addEventListener("click", function () {
      // Get the column index of the clicked header
      const columnIndex = Array.from(this.parentNode.children).indexOf(this);

      // Get all rows in the table body
      const rows = Array.from(table.querySelectorAll("tbody tr"));

      // Toggle the sort order for the clicked column
      if (sortOrder[columnIndex] === "asc") {
        sortOrder[columnIndex] = "desc";
      } else {
        sortOrder[columnIndex] = "asc";
      }

      // Sort the rows based on the value in the clicked column and sort order
      rows.sort((row1, row2) => {
        const cell1 = row1.querySelectorAll("td")[columnIndex];
        const cell2 = row2.querySelectorAll("td")[columnIndex];

        // Sort by numerical value if sorting time before greeting or frequency
        if (columnIndex === 3 || columnIndex === 6) {
          const value1 = parseInt(cell1.innerHTML.replace(/\D/g, ""));
          const value2 = parseInt(cell2.innerHTML.replace(/\D/g, ""));
          const numValue1 = isNaN(value1) ? 0 : value1;
          const numValue2 = isNaN(value2) ? 0 : value2;
          return (
            (sortOrder[columnIndex] === "asc" ? 1 : -1) *
            (numValue1 - numValue2)
          );
        }

        // Sort by custom order if sorting formality
        if (columnIndex === 4) {
          const formalityOrder = [
            "Very Formal",
            "Formal",
            "Casual",
            "Very Casual",
          ];
          const index1 = formalityOrder.indexOf(cell1.innerHTML);
          const index2 = formalityOrder.indexOf(cell2.innerHTML);
          return (
            (sortOrder[columnIndex] === "asc" ? 1 : -1) * (index1 - index2)
          );
        }

        // Sort by alphabetical order by default
        return (
          (sortOrder[columnIndex] === "asc" ? 1 : -1) *
          cell1.innerHTML.localeCompare(cell2.innerHTML)
        );
      });

      // Remove existing rows from the table body
      table.querySelectorAll("tbody tr").forEach((row) => row.remove());

      // Add the sorted rows back to the table body
      rows.forEach((row) => table.querySelector("tbody").appendChild(row));
    });
  });
});
