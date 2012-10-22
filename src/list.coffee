class @List
  @COUNT: 0

  constructor: (id, name) ->
    List.COUNT++
    @items = []
    @input = $('<input type="text"/>')
    @input.keyup this.save
    @id = (if (id?) then id else List.COUNT)
    (if (name?) then @input.val(name) else @input.val("List"))

    @input.attr('id', "list-#{@id}-name")
    @view = this.setUpView()
    $(".board").append(@view)
    this.save()

  setUpView: =>
    listContainer = $('<div class="list-container"></div>')
    listDeleteDiv = $('<div class="list-delete"><p>x</p></div>')
    listDeleteDiv.click this.delete
    listNameDiv = $('<div class="list-name"></div>')
    listNameDiv.append(@input)
    listItemsDiv = $('<div class="list-items"></div>')
    listItemsDiv.attr('id', "list-#{@id}-items")
    addItemDiv = $('<div class="add-item">+</div>')
    addItemDiv.click this.addItem
    listContainer.append(listDeleteDiv)
    listContainer.append(listNameDiv)
    listContainer.append(listItemsDiv)
    listContainer.append(addItemDiv)
    listContainer

  addItem: =>
    console.log(@id)
    @items.push new Item(@id)

  content: =>
    @input.val()

  save: =>
    listName = this.content()
    $.post "list.php",
      'name': listName
      'id': @id
    , (data) ->
      console.log "List save data: " + data

  fetchItems: =>
    $.get "item.php",
      list_id: @id
    , (data) =>
      data = JSON.parse data
      for i, item of data
        @items.push new Item(@id, item['id'], item['data']);

  delete: =>
    return unless confirm("Are you sure?")
    @view.remove()
    $.post "list.php",
      'delete': true
      'id': @id
    , (data) ->
      console.log "List save data: " + data

  @init: ->
    $.get "list.php",
      all: "true"
    , (data) ->
      data = JSON.parse data
      for i, list of data
        list = new List(list['id'], list['name']);
        List.all.push list
        list.fetchItems()
