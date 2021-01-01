// On Load
window.addEventListener("load", () => {
    // Toggle Sidebar
    if (localStorage.getItem("sidebarOpened") == "false") {
        document.body.classList.add("sidebar-toggled");
        document.getElementById("accordionSidebar").classList.add("toggled");
        localStorage.setItem("sidebarOpened", "false");
    } else {
        localStorage.setItem("sidebarOpened", "true");
    }
});

// Window Resize
window.addEventListener("resize", () => {
    if (document.body.clientWidth <= 768) {
        document.body.classList.add("sidebar-toggled");
        document.getElementById("accordionSidebar").classList.add("toggled");
        localStorage.setItem("sidebarOpened", "false");
    } else {
        document.body.classList.remove("sidebar-toggled");
        document.getElementById("accordionSidebar").classList.remove("toggled");
        localStorage.setItem("sidebarOpened", "true");
    }
});

// Sidebar Toggle Button
let sidebarToggle = document.getElementById("sidebarToggle");

sidebarToggle.addEventListener("click", e => {
    let opened =
        window
            .getComputedStyle(e.target, "::after")
            .getPropertyValue("margin-left") == "0px";

    localStorage.setItem("sidebarOpened", opened);
});

// Print Element Function
window.printElm = function(el) {
    var restorepage = $("body").html();
    var printcontent = $("#" + el).clone();
    $("body")
        .empty()
        .html(printcontent);
    window.print();
    $("body").html(restorepage);
};
