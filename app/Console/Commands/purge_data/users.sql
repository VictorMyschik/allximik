insert into public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at,
                          permissions)
values (1, 'admin', 'admin@admin.com', null, '$2y$10$eBxF2IHxwZXBjJSz8sBireM5ZkJUXKbZaIzFJJMq7R2KREA/nhApy', null,
        '2023-08-18 19:23:15', '2023-09-12 14:30:13',
        '{"System.edit": "0", "System.view": "0", "Travel.edit": "0", "Travel.view": "0", "Settings.edit": "0", "Settings.view": "0", "System.delete": "0", "Travel.delete": "0", "platform.index": "1", "Settings.delete": "0", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.systems.attachment": "1"}'),
       (2, 'Allximik', 'mega-ximik@mail.ru', null, '$2y$10$mUgQ.4jsutGb2mDwg2AEB.dr3Mhmb22Zpy5DxAdNAmyeowEZr.eJy', null,
        '2023-09-14 14:38:30', '2023-09-14 14:38:30', null);
