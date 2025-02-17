insert into public.roles (id, slug, name, permissions, created_at, updated_at)
values (1, 'admin', 'Super admin',
        '{"System.edit": "1", "System.view": "1", "Travel.edit": "1", "Travel.view": "1", "Language.edit": "1", "Language.view": "1", "Settings.edit": "1", "Settings.view": "1", "System.delete": "1", "Travel.delete": "1", "platform.index": "1", "Language.delete": "1", "Settings.delete": "1", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.systems.attachment": "1"}',
        '2023-09-12 14:30:05', '2023-09-12 14:30:05');
