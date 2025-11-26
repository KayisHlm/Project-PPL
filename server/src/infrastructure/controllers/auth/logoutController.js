module.exports = async function LogoutController(req, res) {
  try {
    return res.status(200).json({ code: 200, message: "Logged out" });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error" });
  }
}
