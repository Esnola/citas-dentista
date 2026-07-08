
INSERT INTO appointment_reminder_preferences VALUES(1,'whatsapp',1,1,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(2,'whatsapp',2,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(3,'whatsapp',3,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(4,'whatsapp',7,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(5,'email',1,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(6,'email',2,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(7,'email',3,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(8,'email',7,0,'2026-07-08 08:16:39','2026-07-08
INSERT INTO twilio_content_templates VALUES(1,'Dos Botones Nuevo','HX3e116fa6be92c8ef9db84b65c383d5bc',1,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
INSERT INTO twilio_content_templates VALUES(2,'Dos botones Antiguo','HXdea6aee77629b70b2ca3298e0e2ec5f2',0,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
INSERT INTO twilio_content_templates VALUES(3,'Confirmar Texto con Emoji','HX28712cac47e020331237e0dfb9228aaf',0,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
INSERT INTO twilio_content_templates VALUES(4,'Confirmar Texto','HX94dfe8732cc8177e79e8003da08be354',0,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
CREATE TABLE IF NOT EXISTS "whatsapp_dispatch_settings" ("id" integer primary key autoincrement not null, "enabled" tinyint(1) not null default '1', "hours" text not null default '["09:00","12:00","15:00"]', "created_at" datetime, "updated_at" datetime);
INSERT INTO whatsapp_dispatch_settings VALUES(1,0,'["15:00"]','2026-07-08 02:39:11','2026-07-08 08:19:33');
CREATE TABLE IF NOT EXISTS "whatsapp_credentials" ("id" integer primary key autoincrement not null, "mode" varchar not null default 'sandbox', "api_key_sid" text, "api_key_secret" text, "selected" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, "status_callback_url" varchar);
INSERT INTO whatsapp_credentials VALUES(1,'sandbox','eyJpdiI6IkgxK3V6bS9mTDBISnNaV2lGK1lEZHc9PSIsInZhbHVlIjoicms4SUxrWkpnVXVIbmVwYW4zWmhzQmpSOENLTGxiQ1o2ZHozN0xKWlo3S1c2TWc0LzdCRDJ6TU5abTc2SWlDbiIsIm1hYyI6Ijc3OTQwMTJlZjc4MGUxYmI0YjBmYjExNDUwZmNlNmJmZDJlNGFmZjhlYTFjNGM5ZGU5M2E5YWM5MTI3NDk4ZGIiLCJ0YWciOiIifQ==','eyJpdiI6ImpuaytGKzA0V1dHYWQ3dmQzay9oeGc9PSIsInZhbHVlIjoiaVRCQ2RvVWNsRlVDa1NBb3VRZmZPY3Q1Uy9jb2RtcHZDOVM5djZhN0x5eGZwck9nMEtlRk0rZHVvZ0R6dWs4dSIsIm1hYyI6IjdjYWNjZjlkY2IzZTY2NmNjZDdlN2U2ZWM3NmYxYWQ4NmU1OTY0OGRhMzljNjdjYTM1MGQ0YWIyYmFlNTY3NDQiLCJ0YWciOiIifQ==',1,'2026-07-08 02:37:58','2026-07-08 08:47:50','https://chery-precranial-extemporarily.ngrok-free.dev/webhooks/twilio/whatsapp-status');
CREATE TABLE IF NOT EXISTS "whatsapp_sender_numbers" ("id" integer primary key autoincrement not null, "whatsapp_credential_id" integer not null, "prefix" varchar not null default '+34', "number" varchar not null, "selected" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, "name" varchar, foreign key("whatsapp_credential_id") references "whatsapp_credentials"("id") on delete cascade);
INSERT INTO whatsapp_sender_numbers VALUES(8,1,'+1','5559355880',0,'2026-07-08 08:55:29','2026-07-08 10:03:51','Pruebas');
INSERT INTO whatsapp_sender_numbers VALUES(9,1,'+1','4155238886',0,'2026-07-08 08:56:09','2026-07-08 10:03:51','Sender Clinica');
INSERT INTO whatsapp_sender_numbers VALUES(10,1,'+1','2515013894',1,'2026-07-08 08:56:48','2026-07-08 10:03:51','Sender Juan Jota');
COMMIT;
