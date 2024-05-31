// script.js

$(document).ready(function() {
  // Sample data (replace with your actual data or fetch from an API)
  var sharesData = [
    { company: "Company A", symbol: "A", price: 100, change: "+2.5%" },
    { company: "Company B", symbol: "B", price: 200, change: "-1.2%" },
    { company: "Company C", symbol: "C", price: 150, change: "+0.8%" },
  ];

  // Function to populate share listings
  function populateShareListings() {
    var tbody = $('#share-listings tbody');
    tbody.empty();

    $.each(sharesData, function(index, share) {
      var row = $('<tr>');
      row.append($('<td>').text(share.company));
      row.append($('<td>').text(share.symbol));
      row.append($('<td>').text(share.price));
      row.append($('<td>').text(share.change));
      tbody.append(row);
    });
  }

  // Initial population of share listings
  populateShareListings();
});
