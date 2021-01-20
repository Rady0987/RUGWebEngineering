from csv import reader

def string_to_array(string):
	string = string[1:-1]
	xs = string.split(", ")
	for i, x in enumerate(xs):
		xs[i] = xs[i][1:-1].replace('"', '')
	return xs

def sql_insert_prefix(table_name, variables):
	for i, x in enumerate(variables):
		variables[i] = "`" + x + "`"
	return "INSERT INTO `" + table_name + "` (" + ", ".join(variables) + ") VALUES "

def sql_relation_insertion(table_name, second_var_name, relation_list, tuples_per_query):
	output = ""
	prefix = sql_insert_prefix(table_name, ["movie", second_var_name])
	tuples = list()
	for relation in relation_list:
		tuples.append("(" + str(relation[0]) + ", " + str(relation[1]) + ")")
		if len(tuples) >= tuples_per_query:
			output += prefix
			output += ", ".join(tuples) + ";\n"
			tuples.clear()
	if len(tuples) > 0:
		output += prefix
		output += ", ".join(tuples) + ";\n"
		tuples.clear()
	return output

def sql_table_insertion(table_name, relation_dict, tuples_per_query):
	output = ""
	prefix = sql_insert_prefix(table_name, ["id", "name"])
	tuples = list()
	for k, v in relation_dict.items():
		tuples.append("(" + str(v) + ", \"" + k + "\")")
		if len(tuples) >= tuples_per_query:
			output += prefix
			output += ", ".join(tuples) + ";\n"
			tuples.clear()
	if len(tuples) > 0:
		output += prefix
		output += ", ".join(tuples) + ";\n"
		tuples.clear()
	return output

def sql_movies_insertion(tuples_per_query):
	output = ""
	prefix = sql_insert_prefix("movies", ["id", "title", "rating", "year", "users_rating", "votes", "metascore", "img_url", "tagline", "description", "runtime", "imdb_url"])
	tuples = list()
	for k, v in movie_dict.items():
		tuples.append("(" + str(k) + ", \"" + "\", \"".join(v.values()) + "\")")
		if len(tuples) >= tuples_per_query:
			output += prefix
			output += ", ".join(tuples) + ";\n"
			tuples.clear()
	if len(tuples) > 0:
		output += prefix
		output += ", ".join(tuples) + ";\n"
		tuples.clear()
	return output

def sql_truncate_all():
	output = ""
	tables = ["actors", "countries", "directors", "genres", "languages", "movies", "movies_actors", "movies_countries", "movies_directors", "movies_genres", "movies_languages"]
	for table in tables:
		output = output + "TRUNCATE TABLE " + table + ";\n"
	return output

def valOrZero(val):
	if val is "":
		return "0"
	return val

actor_dict = dict()
director_dict = dict()
genre_dict = dict()
country_dict = dict()
language_dict = dict()
movie_dict = dict()

movie_actor_list = list()
movie_director_list = list()
movie_genre_list = list()
movie_country_list = list()
movie_language_list = list()

with open('movie.csv', 'r') as read_obj:
	m_id = 1
	a_id = 1
	d_id = 1
	c_id = 1
	g_id = 1
	l_id = 1
	csv_reader = reader(read_obj)
	print("Loading CSV dataset into memory...")
	for row in csv_reader:
		
		actors = string_to_array(row[9])
		directors = string_to_array(row[13])
		genres = string_to_array(row[10])
		countries = string_to_array(row[7])
		languages = string_to_array(row[8])
		
		for actor in actors:
			if not actor in actor_dict:
				actor_dict[actor] = a_id
				a_id += 1
		for director in directors:
			if not director in director_dict:
				director_dict[director] = d_id
				d_id += 1
		for genre in genres:
			if not genre in genre_dict:
				genre_dict[genre] = g_id
				g_id += 1
		for language in languages:
			if not language in language_dict:
				language_dict[language] = l_id
				l_id += 1
		for country in countries:
			if not country in country_dict:
				country_dict[country] = c_id
				c_id += 1
		
		for actor in actors:
			movie_actor_list.append([m_id, actor_dict[actor]])
		
		for director in directors:
			movie_director_list.append([m_id, director_dict[director]])
		
		for genre in genres:
			movie_genre_list.append([m_id, genre_dict[genre]]);
		
		for country in countries:
			movie_country_list.append([m_id, country_dict[country]]);
		
		for language in languages:
			movie_language_list.append([m_id, language_dict[language]]);
		
		attributes = dict()
		attributes['title'] = row[0]
		attributes['rating'] = row[1]
		attributes['year'] = row[2]
		attributes['users_rating'] = row[3]
		attributes['votes'] = row[4].replace(',', '')
		attributes['metascore'] = valOrZero(row[5])
		attributes['img_url'] = row[6]
		attributes['tagline'] = row[11]
		attributes['description'] = row[12]
		attributes['runtime'] = valOrZero(row[14].split(' ')[0])
		attributes['imdb_url'] = row[15]
		
		for key in attributes.keys():
			attributes[key] = attributes[key].replace('"', '')
		
		movie_dict[m_id] = attributes
		m_id += 1
	
	print("Dataset loaded into memory.")
	print("- Resources: " + str(len(movie_dict)) + " movies, " + str(len(actor_dict)) + " actors, " + str(len(director_dict)) + " directors, " + str(len(genre_dict)) + " genres, " + str(len(country_dict)) + " countries, " + str(len(language_dict)) + " languages.")
	print("- Relations: " + str(len(movie_actor_list)) + " movie-actor, " + str(len(movie_director_list)) + " movie-direct, " + str(len(movie_genre_list)) + " movie-genre, " + str(len(movie_country_list)) + " movie-country, " + str(len(movie_language_list)) + " movie-language, ")
with open("dataset.sql", "w") as output_file:
	print("Converting dataset to SQL...")
	movies_per_query = 20
	tuples_per_query = 200
	
	output = ""
	
	output += sql_truncate_all()
	output += sql_movies_insertion(movies_per_query)
	for table in [["actors", actor_dict], ["directors", director_dict], ["genres", genre_dict], ["countries", country_dict], ["languages", language_dict]]:
		output += sql_table_insertion(table[0], table[1], tuples_per_query)
	for table in [["movies_actors", "actor", movie_actor_list], ["movies_directors", "director", movie_director_list], ["movies_genres", "genre", movie_genre_list], ["movies_countries", "country", movie_country_list], ["movies_languages", "language", movie_language_list]]:
		output += sql_relation_insertion(table[0], table[1], table[2], tuples_per_query)
	output_file.write(output)
	print("SQL file succesfully generated!")
