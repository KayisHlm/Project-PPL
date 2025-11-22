require("dotenv").config();
const UserRepository = require("./src/infrastructure/repositories/userRepository");

async function testFindByEmail() {
    const userRepo = new UserRepository();
    
    // Ganti dengan email yang sudah ada di database kamu
    const testEmail = "admin@platform.com";
    
    console.log("===========================================");
    console.log("    TEST FIND USER BY EMAIL");
    console.log("===========================================\n");
    
    console.log("🔍 Mencari user dengan email:", testEmail);
    
    try {
        const user = await userRepo.findByEmail(testEmail);
        
        if (user) {
            console.log("\n✅ User ditemukan!");
            console.log("ID:", user.id);
            console.log("Email:", user.email);
            console.log("Role:", user.role);
            console.log("Status:", user.status);
            console.log("Seller ID:", user.seller_id || "Bukan seller");
            console.log("Shop Name:", user.shop_name || "-");
            console.log("\n⚠️ Password hash:", user.password.substring(0, 20) + "...");
        } else {
            console.log("\n❌ User tidak ditemukan!");
            console.log("Email ini belum terdaftar di database.");
        }
        
        console.log("\n===========================================");
        console.log("✅ Test selesai!");
        console.log("===========================================");
        
        process.exit(0);
    } catch (error) {
        console.error("\n===========================================");
        console.error("❌ Error:", error.message);
        console.error("===========================================");
        process.exit(1);
    }
}

testFindByEmail();