class User {
    constructor(id, email, password, role, created_at, updated_at) {
        this.id = id;
        this.email = email;
        this.password = password;
        this.role = role;
        this.created_at = created_at;
        this.updated_at = updated_at;
    };
}

modules.export = User;