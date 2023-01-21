User
- user_id (PK)
- username
- password
- type (CLIENT, SUPPLIER, ADMIN)
- status (ADMIN/CLIENT = PENDING, ACTIVE) (SUPPLIER = PENDING, APPROVED, REJECTED)
- firstname
- lastname
- mobile
- address
- avatar
- facebook_url
- portfolio_url
- about
- created
- updated

Post
- post_id (PK)
- user_id
- caption
- image_ids
- status (PENDING, PUBLISHED)
- created
- updated

Image
- image_id (PK)
- path
- created
- updated

Booking
- booking_id (PK)
- user_id_client
- user_id_supplier
- schedule_date
- status (PENDING, ACCEPTED, COMPLETED, CANCELLED)
- note
- created
- updated

Rating
- rating_id (PK)
- user_id_client
- user_id_supplier
- message
- rate
- created
- updated

Likes
- like_id (PK)
- user_id
- post_id
- created
- updated

Comment
- comment_id (PK)
- user_id
- post_id
- message
- created
- updated

Message
- message_id (PK)
- user_id_client
- user_id_supplier
- is_main (main conversation)
- sender (SUPPLIER, CLIENT)
- text
- created
- updated