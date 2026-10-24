import app from "flarum/admin/app";

app.initializers.add("nearata-gif-avatars", () => {
  app.registry.for("nearata-gif-avatars").registerPermission(
    {
      icon: "fas fa-user-tie",
      label: app.translator.trans(
        "nearata-gif-avatars.admin.permissions.use_gifs"
      ),
      permission: "nearata-gif-avatars.use-gifs",
    },
    "start"
  );
});