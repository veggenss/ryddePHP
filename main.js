// oppdater bruker data
// route.post("/updateUserData", requireLogin, async (req, res) => {
//     try {
//         const { username, newPwd } = req.body;

//         if (newPwd !== "") {
//             const salt = await bcrypt.genSalt(10);
//             const hashedPwd = await bcrypt.hash(newPwd, salt);
//             const updateQuery = "UPDATE user SET username = ?, password = ? WHERE id = ?";
//             await conn(updateQuery, [username, hashedPwd, req.session.user.id]);

//             req.session.user.username = username;

//             res.json({ success: true, message: "credentials updated!" });
//         }
//         else if (!username || username.trim() === "") {
//             res.json({ success: true, message: "Brukernavn er tomt" });
//         }
//         else {
//             const updateQuery = "UPDATE user SET username = ? WHERE id = ?";
//             await conn(updateQuery, [username, req.session.user.id]);

//             req.session.user.username = username;

//             res.json({ success: true, message: "credentials updated!" });
//         };
//     }
//     catch (err) {
//         console.error(err);
//         res.status(500).json({ success: false, message: "updateUserData: Error occured" });
//     };
// });
