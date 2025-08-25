document.addEventListener("DOMContentLoaded", () => {
  // Mobile Menu Toggle
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle")
  const mainNav = document.querySelector(".main-nav")

  if (mobileMenuToggle && mainNav) {
    mobileMenuToggle.addEventListener("click", function () {
      mainNav.classList.toggle("active")

      // Toggle hamburger to X
      const spans = this.querySelectorAll("span")
      if (mainNav.classList.contains("active")) {
        spans[0].style.transform = "rotate(45deg) translate(5px, 5px)"
        spans[1].style.opacity = "0"
        spans[2].style.transform = "rotate(-45deg) translate(5px, -5px)"
      } else {
        spans[0].style.transform = "none"
        spans[1].style.opacity = "1"
        spans[2].style.transform = "none"
      }
    })
  }

  // Tabs
  const tabHeaders = document.querySelectorAll(".tab-header")

  tabHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      // Remove active class from all headers and panes
      document.querySelectorAll(".tab-header").forEach((h) => h.classList.remove("active"))
      document.querySelectorAll(".tab-pane").forEach((p) => p.classList.remove("active"))

      // Add active class to clicked header
      this.classList.add("active")

      // Show corresponding tab pane
      const tabId = this.getAttribute("data-tab")
      document.getElementById(tabId).classList.add("active")
    })
  })

  // Accordion
  const accordionHeaders = document.querySelectorAll(".accordion-header")

  accordionHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      const accordionItem = this.parentElement
      accordionItem.classList.toggle("active")
    })
  })

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()

      const targetId = this.getAttribute("href")
      if (targetId === "#") return

      const targetElement = document.querySelector(targetId)
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: "smooth",
        })

        // Close mobile menu if open
        if (mainNav && mainNav.classList.contains("active")) {
          mobileMenuToggle.click()
        }
      }
    })
  })
})
