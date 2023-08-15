import './bootstrap';
import ApexCharts from 'apexcharts';

document.addEventListener("DOMContentLoaded", function() {
  var sidebar = document.getElementById("sidebar");
  var sidebarCollapse = document.getElementById("sidebarCollapse");

  sidebar.classList.add("active");

  sidebarCollapse.addEventListener("click", function() {
      sidebar.classList.toggle("active");
  });
});


window.ApexCharts = ApexCharts;
