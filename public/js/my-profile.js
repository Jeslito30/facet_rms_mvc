// Account dropdown
const userProfileBtn = document.getElementById("userProfileBtn")
const accountDropdown = document.getElementById("accountDropdown")

userProfileBtn.addEventListener("click", (e) => {
  e.stopPropagation()
  userProfileBtn.classList.toggle("active")
  accountDropdown.classList.toggle("active")
})

document.addEventListener("click", () => {
  userProfileBtn.classList.remove("active")
  accountDropdown.classList.remove("active")
})

accountDropdown.addEventListener("click", (e) => {
  e.stopPropagation()
})
